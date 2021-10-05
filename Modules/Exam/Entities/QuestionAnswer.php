<?php

namespace Modules\Exam\Entities;

use Illuminate\Http\Request;

/*
 * Класс DTO-характера для удобной работы с полученными от пользователя ответами на вопросы
 *
 * @author Oleg Pyatin
 */
class QuestionAnswer
{
    /**
     * @var string Конкретный ответ пользователя (конкретное значение)
     */
    public $answer;
    /**
     * @var string ID теста для которого приходит ответ (может быть получен и из просроченной вкладки, поэтому для него
     *                    идет проверка в Middleware)
     */
    public $exam_id;
    /**
     * @var string ID вопроса на который дается ответ
     */
    public $question_id;

    /**
     * Функция извлечения данных об ответе пользователя на вопрос из POST-запроса при прохождении теста
     *
     * @param  Request  $request  Входящий запрос (здесь он будет POST-типа)
     * @return  QuestionAnswer  Объект с данными об ответе (используется в функциях логики прохождения теста)
     */
    public static function getFromRequest(Request $request): QuestionAnswer
    {
        $question_answer = new static();

        $question_answer->answer = $request->post('answer') ?? null;
        $question_answer->question_id = $request->post('question_id') ?? null;
        $question_answer->exam_id = $request->post('exam_id') ?? null;

        return $question_answer;
    }
}
