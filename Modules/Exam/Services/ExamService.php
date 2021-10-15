<?php

namespace Modules\Exam\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Models\StandartExam;
use App\Models\StandartQuestion;

use Modules\Exam\Entities\ExamSectionInfo;
use Modules\Exam\Entities\ExamInfo;
use Modules\Exam\Entities\QuestionAnswer;
use Modules\Exam\Entities\ExamResultData;
use Modules\Exam\Entities\ExamAnalyzeResult;
use Modules\Exam\Entities\ExamResultActions;

use Modules\Exam\Services\ExamAttemptService;
use Modules\Exam\Services\ExamResultService;

use Modules\Exam\Repositories\ExamRepository;

/*
 * Сервис организует логику работы с разделом тестов
 *
 * @author Oleg Pyatin
 */
class ExamService
{
    public function __construct(ExamAttemptService $exam_attempt_service, ExamRepository $exam_repository,
                                ExamResultService $exam_result_service)
    {
        $this->exam_attempt_service = $exam_attempt_service;
        $this->exam_result_service = $exam_result_service;
        $this->exam_repository = $exam_repository;
    }

    /**
     * Получение всех имеющихся в базе тестов
     * @return array Возвращаем массив с парами дял удобства вывода на экран
     */
    public function getAllExams()
    {
        $exam_set = $this->exam_repository->getAllExams();
        $pair_exam_set = [];

        for ($i=0; $i<$exam_set->count(); $i+=2) {
            $pair_exam_set[] = [
                'left'=>$exam_set[$i] ?? null,
                'right'=>$exam_set[$i+1] ?? null
            ];
        }

        return $pair_exam_set;
    }

    /**
     * Получение теста для првеью
     *
     * @param string $exam_url  URL нужного теста
     * @return StandartExam  Объект теста
     */
    public function getPreviewExam(string $exam_url): StandartExam
    {
        return $this->exam_repository->getExamByUrl($exam_url);
    }

    /**
     * Функция проверки начал ли пользователь прохождение (рассматриваемого теста)
     *
     * @param  string  $exam_id  Идентификатор теста
     * @return  bool  Результат проверки
     */
    public function checkExamInProcess(string $exam_id): bool
    {
        return $this->exam_attempt_service->checkExamInProcess($exam_id);
    }

    /**
     * Функция запуска теста из превью - если пользователь еще не начинал, делаем полноценные действия при запуске,
     *     если он уже проходит - просто переходим к текущему вопросу
     *
     * @param  string  $exam_id  Идентификатор теста
     * @return  bool  Результат проверки
     */
    public function launchExam(Request $request, string $exam_id)
    {
        if ($request->routeIs("exam.begin")) {
            return $this->beginNewStandartExam($exam_id);
        } else {
            return $this->getQuestionDependingOnDirection(StandartQuestion::CONTINUE_QUESTION_MOVE, $exam_id);
        }
    }

    /**
     * Функция начала нового теста (создаем запись в сессии, запись в БД, выдаем первый вопрос)
     *
     * @param string $exam_id  ID теста который начинаем
     * @return StandartQuestion  Объект вопроса который будет первым
     */
    public function beginNewStandartExam(string $exam_id): StandartQuestion
    {
        $exam_section_info = ExamSectionInfo::getInfoFromSession();
        $new_exam = new ExamInfo($exam_id, $this->exam_repository->getAllQuestionsForExam($exam_id));

        $this->exam_attempt_service->createExamAttemptInDb($new_exam);
        $exam_section_info->addNewExamAttemptIfNoExist($new_exam);

        return $this->exam_repository->getFirstExamQuestion($exam_id);
    }


    /**
     * Универсальная функция обработки запросов во время прохождения теста
     *
     * @param QuestionAnswer $userAnswer  Структура с промедуточными данными прохождения теста
     */
    public function processAnswer(QuestionAnswer $userAnswer)
    {
        if (!empty($userAnswer->answer)) {
            // Пользователь может проходить экзамен в 2-х или больших окнах - в этом случае current_question_id необязательно будет совпадать с присланным,
            //     поэтому можно допустить сохранение ответа от вопроса не совпадающего с current_quest_id
            $this->exam_attempt_service->saveUserAnswer($userAnswer, ExamSectionInfo::getInfoFromSession());
        }
    }

    /**
     * Получение нужного нам вопроса (следующего, предыдущего или др) из БД для генерации страницы вопросы
     *
     * @param  string  $direction  Какой берем вопрос
     * @param  string  $answer_exam_id  ID нужного теста
     * @return  StandartQuestion  Возвращаем объект вопроса
     */
    public function getQuestionDependingOnDirection(string $direction, string $answer_exam_id): StandartQuestion
    {
        switch ($direction) {
            case StandartQuestion::NEXT_QUESTION_MOVE:
                return $this->getNextQuestion($answer_exam_id);
            case StandartQuestion::PREVIOUS_QUESTION_MOVE:
                return $this->getPreviousQuestion($answer_exam_id);
            case StandartQuestion::BEGIN_QUESTION_MOVE:
                return $this->getBeginQuestion($answer_exam_id);
            case StandartQuestion::CONTINUE_QUESTION_MOVE:
                return $this->getContinueQuestion($answer_exam_id);
            default:
                return $this->getNextQuestion($answer_exam_id);
        }
    }

    /**
     * Функция для получения следующего за текущим вопроса (с модификацией теста в сессии, переставлением указателя на него)
     *
     * @param string $exam_id  ID нужного теста
     * @return StandartQuestion
     */
    public function getNextQuestion(string $exam_id): StandartQuestion
    {
        // Id-ки могут быть не сплошным потоком (иметь разрывы в значениях, как они вопросы сохранялись в БД, возможно вперемешку по разным экзаменам), поэтому треубется
        //     делать пробег именно по ассоциативной части

        $exams_set = ExamSectionInfo::getInfoFromSession();
        $answers_set = $exams_set->current_exams[$exam_id]->answers;
        $in_answers_range = true;

        while (key($answers_set)!==(int)$exams_set->current_exams[$exam_id]->current_quest_id && ($in_answers_range!==false)) {
            $in_answers_range = next($answers_set);
        }

        $shift_result = next($answers_set);

        if ($shift_result!==false) {
            $next_question_id = key($answers_set);

            $exams_set->current_exams[$exam_id]->current_quest_id = $next_question_id;
            $exams_set->updateExamSet();
        } else {
            $next_question_id = $exams_set->current_exams[$exam_id]->current_quest_id;
        }

        return $this->exam_repository->getQuestionById($next_question_id);
    }

    /**
     * Функция получения предыдущего к текущему вопроса (если он не первый)
     *
     * @param string $exam_id  ID нужного теста
     * @return StandartQuestion  Объект предыдущего вопроса
     */
    public function getPreviousQuestion(string $exam_id): StandartQuestion
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();

        $answers_set = $exams_set->current_exams[$exam_id]->answers;
        $in_answers_range = true;
        $previous_question_id = key($answers_set);

        while (key($answers_set)!==(int)$exams_set->current_exams[$exam_id]->current_quest_id && ($in_answers_range!==false)) {
            $previous_question_id = key($answers_set);
            $in_answers_range = next($answers_set);
        }

        $exams_set->current_exams[$exam_id]->current_quest_id = $previous_question_id;
        $exams_set->updateExamSet();

        return $this->exam_repository->getQuestionById($previous_question_id);
    }

    /**
     * Функция получения первого вопроса
     *
     * @param string $exam_id  ID нужного теста
     * @return StandartQuestion  Объект начального вопроса
     */
    public function getBeginQuestion(string $exam_id): StandartQuestion
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        $answers_set = $exams_set->current_exams[$exam_id]->answers;
        $beginning_question_id = key($answers_set);

        $exams_set->current_exams[$exam_id]->current_quest_id = $beginning_question_id;
        $exams_set->updateExamSet();

        return $this->exam_repository->getQuestionById($beginning_question_id);
    }

    /**
     * Получение текущего вопроса для теста (на случай если мы например возобновляем прохождение со страницы превью)
     *
     * @param string $exam_id  ID нужного теста
     * @return StandartQuestion  Объект текущего вопроса
     */
    public function getContinueQuestion(string $exam_id): StandartQuestion
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        return $this->exam_repository->getQuestionById($exams_set->current_exams[$exam_id]->current_quest_id);
    }

    /**
     * Получение ответа для текущего вопроса (если таковой имеется, случай когда мы возвращаемся к прошлым вопросам при
     *     движении внутри теста)
     *
     * @param string $exam_id
     * @param string $question_id
     * @return type
     */
    public function getCurrentAnswerForQuestion(string $exam_id, string $question_id)
    {
        return $this->exam_attempt_service->getCurrentAnswerForQuestion($exam_id, $question_id);
    }

    /**
     * Функция для получения порядкового номера текущего вопроса (первый, второй и пр, а не технический ID в БД)
     *
     * @param string $exam_id  ID теста
     * @param string $new_question_id  ID требуемого вопроса
     * @return int
     */
    public function getCurrentNumberQuestion(string $exam_id, string $new_question_id): int
    {
        return $this->exam_attempt_service->getCurrentNumberQuestion($exam_id, $new_question_id);
    }

    /**
     * Получение количества уже полученных от пользователя ответов для выбранного теста
     *
     * @param string $exam_id  ID теста
     * @return int
     */
    public function getCurrentAnswersAmountForExam(string $exam_id): int
    {
        return $this->exam_attempt_service->getCurrentAnswersAmountForExam($exam_id);
    }

    /**
     * Функция получения количества вопросов для нужного теста
     *
     * @param string $exam_id  ID теста
     * @return int  Количество вопросов
     */
    public function getQuestionsAmountForExam(string $exam_id):int
    {
        return $this->exam_repository->getQuestionsAmountForExam($exam_id);
    }

    /**
     * Функция проверки ответов для теста (от стороннего сервиса)
     *
     * @param QuestionAnswer $final_answer  Входные данные последнего запроса теста (нужны чтобы достать его ID)
     * @return ExamResultData Выходные данные экзамена (нужны для вывода справки о результатах)
     */
    public function checkExamAnswers(QuestionAnswer $final_answer): ExamResultData
    {
        return $this->exam_result_service->checkExamAnswers($final_answer);
    }

    /**
     * Функция завершения текущей попытки для выбранного теста (сохраняем ее в БД и убираем из сессии)
     *
     * @param QuestionAnswer $final_answer  Последний запрос для выбранного теста
     * @return void
     */
    public function finishCurrentExamAttempt(QuestionAnswer $final_answer): void
    {
        $this->exam_attempt_service->finishExam($final_answer->exam_id);
        (ExamSectionInfo::getInfoFromSession())->removeExamFromSession($final_answer->exam_id);
    }

    /**
     * Получить тест по ID (функция для внешних сервисов из других модулей)
     *
     * @param string $exam_id  ID теста
     * @return StandartExam  Экзамен который ищем
     */
    public function getExamById(string $exam_id): ?StandartExam
    {
        return $this->exam_repository->getExamById($exam_id);
    }

    /**
     * Получить результаты проверки ответов дял теста (для сторонних сервисов)
     *
     * @param string $exam_id  ID теста
     * @return StandartExam  Экзамен который ищем
     */
    public function checkRightAnswers(Collection $exam_questions, array $user_answers): ?ExamAnalyzeResult
    {
        return $this->exam_result_service->checkRightAnswers($exam_questions, $user_answers);
    }

    /**
     * Получить оценку дял указанной попытки теста (для сторонних сервисов)
     *
     * @param ExamResultActions  $exam_result_actions
     * @param ExamAnalyzeResult  $exam_analyze_result
     * @return mixed  Оценка за попытку теста
     */
    public function getMarkForResult(ExamResultActions $exam_result_actions, ExamAnalyzeResult $exam_analyze_result)
    {
        return $this->exam_result_service->getMarkForResult($exam_result_actions, $exam_analyze_result);
    }
}
