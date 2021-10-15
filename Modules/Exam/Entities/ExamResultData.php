<?php

namespace Modules\Exam\Entities;

use App\Components\BaseDto;
use Illuminate\Database\Eloquent\Collection;

class ExamResultData extends BaseDto
{
    private $exam_name;

    private $right_answers_amount;

    private $questions_total_quantity;

    private $mark;

    private $user_answers;

    private $exam_questions;

    private $answers_show;

    private $exam_right_answers_map;


    public function getExamName()
    {
        return $this->exam_name;
    }

    public function setExamName(string $exam_name)
    {
        $this->exam_name = $exam_name;
    }

    public function getRightAnswersAmount()
    {
        return $this->right_answers_amount;
    }

    public function setRightAnswersAmount(int $right_answers_amount)
    {
        $this->right_answers_amount = $right_answers_amount;
    }

    public function getQuestionsTotalQuantity()
    {
        return $this->questions_total_quantity;
    }

    public function setQuestionsTotalQuantity(int $questions_total_quantity)
    {
        $this->questions_total_quantity = $questions_total_quantity;
    }

    public function getMark()
    {
        return $this->mark;
    }

    public function setMark(string $mark)
    {
        $this->mark = $mark;
    }

    public function getUserAnswers()
    {
        $formatted_user_answers = [];
        foreach ($this->user_answers as $key=>$user_answer) {
            if (is_array($user_answer)) {
                $formatted_user_answers += [ $key=> implode(", ", $user_answer)];
            } else {
                $formatted_user_answers += [ $key=> $user_answer ?? "нет ответа"];
            }
        }
        return $formatted_user_answers;
    }

    public function setUserAnswers(array $user_answers)
    {
        $this->user_answers = $user_answers;
    }

    public function getExamQuestions()
    {
        return $this->exam_questions;
    }

    public function setExamQuestions(Collection $exam_questions)
    {
        $this->exam_questions = $exam_questions;
    }

    public function getAnswersShow()
    {
        return $this->answers_show;
    }

    public function setAnswersShow(string $answers_show)
    {
        $this->answers_show = $answers_show;
    }

    public function getExamRightAnswersMap()
    {
        return $this->exam_right_answers_map;
    }

    public function setExamRightAnswersMap(array $exam_right_answers_map)
    {
        $this->exam_right_answers_map = $exam_right_answers_map;
    }

    public function getRightAnswersPercent(): int
    {
        return (int)ceil(($this->right_answers_amount / $this->questions_total_quantity) * (100.0));
    }
}
