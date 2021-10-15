<?php

namespace Modules\Exam\Services;

use Modules\Exam\Entities\QuestionAnswer;
use Modules\Exam\Entities\ExamSectionInfo;
use Modules\Exam\Entities\ExamInfo;
use Illuminate\Support\Arr;
use Modules\Exam\Repositories\ExamRepository;
use Modules\Exam\Factories\ExamFactory;

/*
 * Подсервис реализующий логику работы с текущей попыткой (больше со стороны сессии)
 *
 * @author Oleg Pyatin
 */
class ExamAttemptService
{
    public function __construct(ExamRepository $exam_repository, ExamFactory $exam_factory)
    {
        $this->exam_repository = $exam_repository;
        $this->exam_factory = $exam_factory;
    }

    /**
     * Создание новой попытки прохождения экзамена в БД и связывание ее с сессией
     *
     * @param  ExamInfo  $exam_info  Подготовленный сессионный объект прохождения экзамена
     * @return  void  Просто сохраняем объект в БД и связываем его с сессией внутри кода функции, ничего не возвращаем
     */
    public function createExamAttemptInDb(ExamInfo $exam_info): void
    {
        $new_attempt = $this->exam_factory->createExamAttempt($this->exam_repository->getExamById($exam_info->exam_id), $exam_info->answers);
        $this->exam_repository->saveNewExamAttempt($new_attempt);
        $exam_info->attempt_id = $new_attempt->id;
    }

    /**
     * Выполнение сохранение ответа (сохраняем в сессию и в репозиторий, передвигаем указатель вопроса)
     *
     * @param QuestionAnswer $answer
     * @param ExamSectionInfo $exams_set
     */
    public function saveUserAnswer(QuestionAnswer $answer, ExamSectionInfo $exams_set)
    {
        if (Arr::has($exams_set->current_exams[$answer->exam_id]->answers, $answer->question_id)) {

            $exams_set->current_exams[$answer->exam_id]->answers[$answer->question_id] = $answer->answer;

            $this->exam_repository->updateExamAttempt($exams_set->current_exams[$answer->exam_id]->attempt_id,
                    $exams_set->current_exams[$answer->exam_id]->answers);
        }

        // Даже в случае если не было ответа, мы перемещаем указатель номера вопроса на текущий/присланный
        $exams_set->current_exams[$answer->exam_id]->current_quest_id = $answer->question_id;

        $exams_set->updateExamSet();
    }

    /**
     * Узнать количество имеющихся ответов пользователя для текущей попытки теста
     *
     * @param  string  $exam_id  ID нужного теста
     * @return  int  Количество имеющихся ответов
     */
    public function getCurrentAnswersAmountForExam(string $exam_id): int
    {
        $answers_set = (ExamSectionInfo::getInfoFromSession())->current_exams[$exam_id]->answers;
        $answers_amount = 0;

        foreach ($answers_set as $answer) {
            if (!empty($answer)) {
                $answers_amount++;
            }
        }

        return $answers_amount;
    }

    /**
     * Функция используется для получения имеющегося ответа если мы возвращаемся к пройденным вопросам
     *
     * @param  string  $exam_id  ID нужного теста
     * @param  string  $question_id  Технический ID вопроса (не порядковый номер)
     * @return  type
     */
    public function getCurrentAnswerForQuestion(string $exam_id, string $question_id)
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        return $exams_set->current_exams[$exam_id]->answers[$question_id] ?? null;
    }

    /**
     * Получение текущего номера вопроса (порядкового)
     *
     * @param  string  $exam_id  ID нужного теста
     * @param  string  $new_question_id  Технический ID вопроса (не порядковый номер)
     * @return  int
     */
    public function getCurrentNumberQuestion(string $exam_id, string $new_question_id): int
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();

        // Используем финт с переворотом массива для получения нужного номера вопроса
        $question_id_set = array_keys($exams_set->current_exams[$exam_id]->answers);
        return array_flip($question_id_set)[$new_question_id] + 1;
    }

    /**
     * Функция проверки начал ли пользователь прохождение (рассматриваемого теста) - если он уже начал,
     *     тогда на экране превью меняем запуск функционала начала на запуск продолжения теста
     *
     * @param  string  $exam_id  Идентификатор теста
     * @return  bool  Результат проверки
     */
    public function checkExamInProcess(string $exam_id): bool
    {
        if (!empty(ExamSectionInfo::getInfoFromSession()->current_exams[$exam_id])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Отмечаем попытку теста в БД как завершенную
     *
     * @param  string  $exam_id  Идентификатор теста который заврешаем
     * @return  void  Просто отмечаем ничего возвращаем
     */
    public function finishExam(string $exam_id): void
    {
        $attempt_id = (ExamSectionInfo::getInfoFromSession())->current_exams[$exam_id]->attempt_id;
        $this->exam_repository->finishExamAttempt($attempt_id);
    }
}
