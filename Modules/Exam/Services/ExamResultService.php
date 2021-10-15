<?php

namespace Modules\Exam\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Models\StandartExam;
use App\Models\StandartQuestion;

use Modules\Exam\Entities\ExamSectionInfo;
use Modules\Exam\Entities\ExamResult;
use Modules\Exam\Entities\ExamResultData;
use Modules\Exam\Entities\ExamResultActions;
use Modules\Exam\Entities\ExamAnalyzeResult;
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
        $exam = $this->exam_repository->getExamById($final_answer->exam_id);
        $exams_set = ExamSectionInfo::getInfoFromSession();
        $current_answers_set = $exams_set->current_exams[$final_answer->exam_id]->answers;
        $exam_result_actions = ExamResultActions::getFromJson($exam->result_actions);

        $exam_analyze_result = $this->checkRightAnswers($exam->questions, $current_answers_set);

        $mark = $this->getMarkForResult($exam_result_actions, $exam_analyze_result);

        return ExamResultData::loadFromArray([
            'exam_name'=>$exam->name,
            'right_answers_amount'=>$exam_analyze_result->right_answers_amount,
            'questions_total_quantity'=>$exam->questions->count(),
            'mark'=>$mark,
            'user_answers'=>$current_answers_set,
            'exam_questions'=>$exam->questions,
            'answers_show'=>$exam_result_actions->answers_show,
            'exam_right_answers_map'=>$exam_analyze_result->exam_right_answers_map
        ]);
    }

    public function getMarkForResult(ExamResultActions $exam_result_actions, ExamAnalyzeResult $exam_analyze_result)
    {
        switch ($exam_result_actions->type) {

            case ExamResult::STANDART_RESULT_DISPLAY:
                return $this->getMarkByStandartRules($exam_result_actions, $exam_analyze_result);

            case ExamResult::CUSTOM_RESULT_DISPLAY:
                return $this->getMarkByCustomRules();

            default:
                return "";
        }
    }

    public function getMarkByStandartRules(ExamResultActions $exam_result_actions, ExamAnalyzeResult $exam_analyze_result): string
    {
        switch ($exam_result_actions->process_type) {
            case ExamResult::STRICT_PROCESS_TYPE:

                $ratio = (float) ($exam_analyze_result->right_answers_amount / $exam_analyze_result->questions_total_quantity);
                $degrees_mark = (float)$exam_result_actions->degrees_amount * $ratio;
                $integer_mark = (int)ceil($degrees_mark);

                return $this->getMessageDependOnDegreesAmount($exam_result_actions, $integer_mark);

            case ExamResult::HARD_PROCESS_TYPE:

                $ratio = (float)($exam_analyze_result->right_answers_amount / $exam_analyze_result->questions_total_quantity);

                // Делаем усложнение (по типу квадратичной функции, со снижением силы сбивки)
                $ratio *= (float) ( (($exam_analyze_result->questions_total_quantity - $exam_analyze_result->right_answers_amount) / 2)
                                        + $exam_analyze_result->right_answers_amount) / $exam_analyze_result->questions_total_quantity;

                $degrees_mark = (float)$exam_result_actions->degrees_amount * $ratio;
                $integer_mark = (int)ceil($degrees_mark);

                return $this->getMessageDependOnDegreesAmount($exam_result_actions, $integer_mark);

            default:
                return "";
        }
    }

    public function getMessageDependOnDegreesAmount(ExamResultActions $exam_result_actions, int $integer_mark): string
    {
        $marks_degree = ($exam_result_actions->marks_degree_show)?" (".$integer_mark." из ".$exam_result_actions->degrees_amount." возможных)":"";

        switch ($exam_result_actions->degrees_amount) {
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


    public function checkRightAnswers(Collection $exam_questions, array $user_answers): ExamAnalyzeResult
    {
        $exam_analyze_result = ExamAnalyzeResult::loadFromArray([
            'right_answers_amount'=>0,
            'questions_total_quantity'=>$exam_questions->count(),
            'exam_right_answers_map'=>[],
            'user_answers'=>$user_answers
        ]);

        foreach ($exam_questions as $question) {

            switch ($question->quest_type) {

                case StandartQuestion::SINGLE_CHOICE_QUEST_TYPE:
                    $this->checkSingleChoiceQuestion($question, $exam_analyze_result);
                    break;

                case StandartQuestion::MULTIPLE_CHOICE_QUEST_TYPE:
                    $this->checkMultipleChoiceQuestion($question, $exam_analyze_result);
                    break;

                default:
                    break;
            }
        }

        return $exam_analyze_result;
    }

    public function checkSingleChoiceQuestion(StandartQuestion $question, ExamAnalyzeResult $exam_analyze_result): void
    {
        $user_answer = $exam_analyze_result->user_answers[$question->id];

        $question_answers = json_decode($question->answers, true);
        $map_right_answers = array_combine(array_column($question_answers, "text"), array_column($question_answers, "right"));

        if (($map_right_answers[$user_answer] ?? false) === "true") {
            $exam_analyze_result->right_answers_amount++;
            $exam_analyze_result->exam_right_answers_map += [ $question->id =>[
                'right'=>true
            ] ];
        } else {
            $exam_analyze_result->exam_right_answers_map += [ $question->id =>[
                'right'=>false,
                'right_answer'=> (!empty($user_answer)) ? array_flip($map_right_answers)["true"] : null    // В случае если пользователь дал неправильный ответ, добавляем туда правильный
            ] ];
        }
    }

    public function checkMultipleChoiceQuestion(StandartQuestion $question, ExamAnalyzeResult $exam_analyze_result): void
    {
        $user_answer = $exam_analyze_result->user_answers[$question->id];
        $question_answers = json_decode($question->answers, true);

        $map_right_answers = array_combine(array_column($question_answers, "text"), array_column($question_answers, "right"));
        $all_right_answers = array_filter($map_right_answers, function ($value) { return $value==="true"; });
        $exact = true;

        if (is_array($user_answer) && count($user_answer) === count($all_right_answers)) {
            foreach ($user_answer as $index=>$answer) {

                if ($map_right_answers[$answer] !=="true") {
                    $exact = false;
                }
            }
        } else {
            $exact = false;
        }


        if ($exact) {
            $exam_analyze_result->right_answers_amount++;
            $exam_analyze_result->exam_right_answers_map += [ $question->id =>[
                'right'=>true
            ] ];
        } else {
            $exam_analyze_result->exam_right_answers_map += [ $question->id =>[
                'right'=>false,
                'right_answer'=> (!empty($user_answer)) ? implode(", ", array_keys($all_right_answers)) : null    // В случае если пользователь дал неправильный ответ, добавляем туда правильный
            ] ];
        }
    }

}

