<?php

namespace Modules\Exam\Entities;

use App\Components\BaseDto;

class ExamAnalyzeResult extends BaseDto
{
    public $right_answers_amount;

    public $questions_total_quantity;
    /**
     * @var array  Массив для сохранения - какие ответы правильные, а какие нет (в дальнейшем для удобства вывода)
     */
    public $exam_right_answers_map;

    public $user_answers;

    public function getRightAnswersPercent(): int
    {
        return (int)ceil(($this->right_answers_amount / $this->questions_total_quantity) * (100.0));
    }
}
