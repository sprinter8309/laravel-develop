<?php

namespace Modules\Exam\Services;

use App\Models\StandartExam;
use App\Models\StandartQuestion;

use Modules\Exam\Entities\ExamSectionInfo;
use Modules\Exam\Entities\ExamInfo;
use Modules\Exam\Entities\QuestionAnswer;
use Modules\Exam\Entities\ExamResultData;

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

    public function getPreviewExam(string $exam_url): StandartExam
    {
        return $this->exam_repository->getExamByUrl($exam_url);
    }



    public function beginNewStandartExam(string $exam_id): StandartQuestion
    {
        $exam_section_info = ExamSectionInfo::getInfoFromSession();
        $new_exam = new ExamInfo($exam_id, $this->exam_repository->getAllQuestionsForExam($exam_id));

        $exam_section_info->addNewExamAttemptIfNoExist($new_exam);

        return $this->exam_repository->getFirstExamQuestion($exam_id);
    }

    // Универсальная функция обработки запросов во время прохождения теста
    public function processAnswer(QuestionAnswer $userAnswer)
    {
        if (!empty($userAnswer->answer)) {
            // Пользователь может проходить экзамен в 2-х или больших окнах - в этом случае current_question_id необязательно будет совпадать с присланным,
            //     поэтому можно допустить сохранение ответа от вопроса не совпадающего с current_quest_id
            $this->exam_attempt_service->saveUserAnswer($userAnswer, ExamSectionInfo::getInfoFromSession());
        }
    }


    public function getQuestionDependingOnDirection(string $direction, string $answer_exam_id): StandartQuestion
    {
        switch ($direction) {
            case StandartQuestion::NEXT_QUESTION_MOVE:
                return $this->getNextQuestion($answer_exam_id);
            case StandartQuestion::PREVIOUS_QUESTION_MOVE:
                return $this->getPreviousQuestion($answer_exam_id);
            case StandartQuestion::BEGIN_QUESTION_MOVE:
                return $this->getBeginQuestion($answer_exam_id);
            default:
                return $this->getNextQuestion($answer_exam_id);
        }
    }


    public function getNextQuestion(string $exam_id): StandartQuestion
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        $answers_set = $exams_set->current_exams[$exam_id]->answers;
        $in_answers_range = true;  // Контроль чтобы не было зацикливаний при проходе

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


    public function getPreviousQuestion(string $exam_id): StandartQuestion
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();

        $answers_set = $exams_set->current_exams[$exam_id]->answers;
        $in_answers_range = true;  // Контроль чтобы не было зацикливаний при проходе
        $previous_question_id = key($answers_set);

        while (key($answers_set)!==(int)$exams_set->current_exams[$exam_id]->current_quest_id && ($in_answers_range!==false)) {
            $previous_question_id = key($answers_set);
            $in_answers_range = next($answers_set);
        }

        $exams_set->current_exams[$exam_id]->current_quest_id = $previous_question_id;
        $exams_set->updateExamSet();

        return $this->exam_repository->getQuestionById($previous_question_id);
    }


    public function getBeginQuestion(string $exam_id): StandartQuestion
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        $answers_set = $exams_set->current_exams[$exam_id]->answers;
        $beginning_question_id = key($answers_set);

        $exams_set->current_exams[$exam_id]->current_quest_id = $beginning_question_id;
        $exams_set->updateExamSet();

        return $this->exam_repository->getQuestionById($beginning_question_id);
    }



    public function getCurrentAnswerForQuestion(string $exam_id, string $question_id): ?string
    {
        return $this->exam_attempt_service->getCurrentAnswerForQuestion($exam_id, $question_id);
    }


    public function getCurrentNumberQuestion(string $exam_id, string $new_question_id): int
    {
        return $this->exam_attempt_service->getCurrentNumberQuestion($exam_id, $new_question_id);
    }

    public function getCurrentAnswersAmountForExam(string $exam_id): int
    {
        return $this->exam_attempt_service->getCurrentAnswersAmountForExam($exam_id);
    }



    public function checkExamAnswers(QuestionAnswer $final_answer): ExamResultData
    {
        return $this->exam_result_service->checkExamAnswers($final_answer);
    }

    public function finishCurrentExamAttempt(QuestionAnswer $final_answer): void
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        $exams_set->removeExamFromSession($final_answer->exam_id);
    }
}
