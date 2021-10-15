<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * Модель для работы с имеющимися у пользователя попытками прозождения тестов (текущих или завершенных)
 *
 * @author Oleg Pyatin
 */
class ExamAttempt extends Model
{
    use HasFactory;

    protected $table = 'exam_attempt';

    /**
     * Добавочное поле для вывода процента правильных ответов в списке попыток
     */
    public $right_answers_percent;

    public $hidden = [
        'user_id',
        'updated_at'
    ];

    /**
     * Константа для обозначения что тест находится в стадии прохождения
     */
    public const PROCESS_EXAM_STATUS = 'process';
    /**
     * Константа для обозначения что тест завершился (может пользователь прошел, может он вышел, в любом случае тест завершен)
     */
    public const FINISH_EXAM_STATUS = 'finish';
    /**
     * Таблица для перевода внутреннего представления статуса на русский язык (нужна при выводе попыток пользователя)
     */
    public const RUSSIAN_STATUS_SIGN = [
        self::PROCESS_EXAM_STATUS => 'В процессе',
        self::FINISH_EXAM_STATUS => 'Завершено'
    ];
}
