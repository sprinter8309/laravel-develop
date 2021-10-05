<?php

namespace Modules\Exam\Services;

use Modules\Exam\Entities\QuestionAnswer;
use Modules\Exam\Entities\ExamSectionInfo;

use Illuminate\Support\Arr;

class ExamAttemptService
{
    public function saveUserAnswer(QuestionAnswer $answer, ExamSectionInfo $exams_set)
    {
        if (Arr::has($exams_set->current_exams[$answer->exam_id]->answers, $answer->question_id)) {

            $exams_set->current_exams[$answer->exam_id]->answers[$answer->question_id] = $answer->answer;
        }

        // Даже в случае если не было ответа, мы перемещаем указатель номера вопроса на текущий/присланный
        $exams_set->current_exams[$answer->exam_id]->current_quest_id = $answer->question_id;

        $exams_set->updateExamSet();
    }

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

    // Функция используется для получения имеющегося ответа если мы возвращаемся к пройденным вопросам
    public function getCurrentAnswerForQuestion(string $exam_id, string $question_id): ?string
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();
        return $exams_set->current_exams[$exam_id]->answers[$question_id] ?? null;
    }

    public function getCurrentNumberQuestion(string $exam_id, string $new_question_id): int
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();

        // Используем финт с переворотом массива для получения нужного номера вопроса
        $question_id_set = array_keys($exams_set->current_exams[$exam_id]->answers);
        return array_flip($question_id_set)[$new_question_id] + 1;
    }
}
