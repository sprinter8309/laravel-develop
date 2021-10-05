<?php

namespace Modules\Exam\Services;

use App\Models\StandartExam;

use Modules\Exam\Entities\ExamSectionInfo;
use Modules\Exam\Entities\ExamResult;
use Modules\Exam\Entities\ExamResultData;
use Modules\Exam\Entities\QuestionAnswer;

use Modules\Exam\Repositories\ExamRepository;


class ExamResultService
{
    public function __construct(ExamRepository $exam_repository)
    {
        $this->exam_repository = $exam_repository;
    }

    public function checkExamAnswers(QuestionAnswer $final_answer): ExamResultData
    {
        // Получение числа правильных ответов
        $right_answers_amount = 0;

        $exams_set = ExamSectionInfo::getInfoFromSession();
        $current_answers_set = $exams_set->current_exams[$final_answer->exam_id]->answers;

        // Получение всех вопрос экзамена
        $exam_questions = $this->exam_repository->getAllQuestionsForExam($final_answer->exam_id); // $this->getAllQuestionsForExam($final_answer->exam_id);

        // Массив для сохранения - какие ответы правильные, а какие нет (в дальнейшем для удобства вывода)
        $exam_right_answers_map = [];

        foreach ($exam_questions as $question) {
            $user_answer = $current_answers_set[$question->id];
            $question_answers = json_decode($question->answers, true);

            // Получаем карту правильности ответов (так можем проверить правильность ответа пользователя на вопрос простым
            // выбором элемента массива)
            $map_right_answers = array_combine(array_column($question_answers, "text"), array_column($question_answers, "right"));

            if (($map_right_answers[$user_answer] ?? false) === "true") {
                $right_answers_amount++;
                $exam_right_answers_map += [ $question->id =>[
                    'right'=>true
                ] ];
            } else {
                $exam_right_answers_map += [ $question->id =>[
                    'right'=>false,
                    'right_answer'=> (!empty($user_answer)) ? array_flip($map_right_answers)["true"] : null    // В случае если пользователь дал ответ, добавляем туда правильный
                ] ];
            }
        }

//        dd($current_answers_set);
//        dd($right_answers_amount);

        // Вывод результатов в соответствии с ними
        $exam = $this->exam_repository->getExamById($final_answer->exam_id);

        $result_actions_data = json_decode($exam->result_actions, true);

        // ! здесь сменить $exam на $result_actions_data

        //$result_actions_data = json_decode($exam_object->result_actions, true);
        $mark = $this->getMarkForResult($exam, $right_answers_amount, $exam_questions->count());

        //dd($mark);
        return ExamResultData::loadFromArray([
            'exam_name'=>$exam->name,
            'right_answers_amount'=>$right_answers_amount,
            'questions_total_quantity'=>$exam_questions->count(),
            'mark'=>$mark,
            'user_answers'=>$current_answers_set,
            'exam_questions'=>$exam_questions,
            'answers_show'=>$result_actions_data["answers_show"],
            'exam_right_answers_map'=>$exam_right_answers_map
        ]);
    }


    public function getMarkForResult(StandartExam $exam, int $right_answers_amount, int $questions_total_quantity)
    {
        $result_actions_data = json_decode($exam->result_actions, true);


        switch ($result_actions_data["type"]) {

            case ExamResult::STANDART_RESULT_DISPLAY:
                return $this->getMarkByStandartRules($result_actions_data["degrees_amount"], $result_actions_data["process"],
                        $right_answers_amount, $questions_total_quantity, $result_actions_data["marks_degree_show"] ?? false);

            case ExamResult::CUSTOM_RESULT_DISPLAY:
                return $this->getMarkByCustomRules();

            default:
                return "";
        }
    }

    
    public function getMarkByStandartRules(string $degrees_amount, string $process_type, int $right_answers_amount, int $questions_total_quantity, bool $marks_degree_show): string
    {
        switch ($process_type) {
            case ExamResult::STRICT_PROCESS_TYPE:

                $ratio = (float) ($right_answers_amount / $questions_total_quantity);
                $degrees_mark = (float)$degrees_amount * $ratio;
                $integer_mark = (int)ceil($degrees_mark);

                return $this->getMessageDependOnDegreesAmount($degrees_amount, $integer_mark, $marks_degree_show);

            case ExamResult::HARD_PROCESS_TYPE:

                $ratio = (float)($right_answers_amount / $questions_total_quantity);

                // Делаем усложнение (по типу квадратичной функции, со снижением силы сбивки)
                $ratio *= (float) ($questions_total_quantity-$right_answers_amount / 2 + $right_answers_amount) / $questions_total_quantity;

                $degrees_mark = (float)$degrees_amount * $ratio;
                $integer_mark = (int)ceil($degrees_mark);

                return $this->getMessageDependOnDegreesAmount($degrees_amount, $integer_mark, $marks_degree_show);

            default:
                return "";
        }
    }

    public function getMessageDependOnDegreesAmount(string $total_degrees_amount, int $integer_mark, bool $marks_degree_show): string
    {
        $marks_degree = ($marks_degree_show)?" (".$integer_mark." из ".$total_degrees_amount." возможных)":"";

        switch ($total_degrees_amount) {
            case ExamResult::STANDART_THREE_DEGREES:
                return ExamResult::getStandartThreeLevelMarkMessage($integer_mark) . $marks_degree;
            case ExamResult::STANDART_FOUR_DEGREES:
                return ExamResult::getStandartFourLevelMarkMessage($integer_mark) . $marks_degree;
            case ExamResult::STANDART_FIVE_DEGREES:
                return ExamResult::getStandartFiveLevelMarkMessage($integer_mark) . $marks_degree;
            default:
                return "";
        }
    }

    public function getMarkByCustomRules()
    {
        // Пока ничего не возвращаем
        return "";
    }
}
