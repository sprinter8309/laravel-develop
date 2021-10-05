<?php

namespace Modules\Exam\Entities;

use Illuminate\Database\Eloquent\Collection;

/*
 * Объект для хранения данных о прохождении конкретного теста пользователя в сессии (включается в массив тестов
 *     для общего объекта)
 *
 * @author Oleg Pyatin
 */
class ExamInfo
{
    public $exam_id;
    public $current_quest_id;
    public $answers;
    public $marks_degree_show;

    public function __construct(int $exam_id, Collection $questions)
    {
        $this->exam_id = $exam_id;
        $this->answers = [];
        $this->current_quest_id = $questions->first()->id ?? null;

        // Заполнение массива ответов (в начале просто null - пользователь не ответил)
        foreach ($questions as $question) {
            $this->answers[$question->id] = null;
        }
    }
}

