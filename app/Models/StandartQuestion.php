<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с объектом типового вопроса (несгенерированного автоматически)
 *
 * @author Oleg Pyatin
 */
class StandartQuestion extends Model
{
    use HasFactory;

    protected $table = 'standart_question';

    /**
     * Обозначение вопроса с одним вариантом ответа у пользователя
     */
    public const SINGLE_CHOICE_QUEST_TYPE = 'single_choice';
    /**
     * Обозначение вопроса с множеством вариантом ответов у пользователя
     */
    public const MULTIPLE_CHOICE_QUEST_TYPE = 'multiple_choice';
    /**
     * Обозначение перехода к следующему вопросу (в роутинге навигации в тестах)
     */
    public const NEXT_QUESTION_MOVE = "next";
    /**
     * Обозначение перехода к предыдущему вопросу (в роутинге навигации в тестах)
     */
    public const PREVIOUS_QUESTION_MOVE = "previous";
    /**
     * Обозначение перехода к начальному вопросу (в роутинге навигации в тестах)
     */
    public const BEGIN_QUESTION_MOVE = "begin";
    /**
     * Случай когда мы возобновляем экзамен из окна превью
     */
    public const CONTINUE_QUESTION_MOVE = "continue";
}
