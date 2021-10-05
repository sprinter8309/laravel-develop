<?php

namespace Modules\Exam\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\StandartQuestion;
use App\Models\StandartExam;

/*
 * Класс-репозиторий для работы с объектами модуля тестов в БД
 *
 * @author Oleg Pyatin
 */
class ExamRepository
{
    /**
     * Получаем весь список тестов (для главной страницы тестов)
     *
     * @return  Collection  Список со всеми тестами
     */
    public function getAllExams(): Collection
    {
        return StandartExam::all();
    }

    /**
     * Получаем первый вопрос для нужного теста (для функции начала прохождения теста)
     *
     * @param  string  $exam_id  Идентификатор теста
     * @return  StandartQuestion  Объект первого вопроса для теста
     */
    public function getFirstExamQuestion(string $exam_id): StandartQuestion
    {
        return StandartQuestion::where('exam_id', $exam_id)
                ->orderBy('id')
                ->first();
    }

    /**
     * Получить тест по его Url (используется для страницы превью теста)
     *
     * @param  string  $exam_url  Url-нужного теста
     * @return  StandartExam  Объект теста которого ищем
     */
    public function getExamByUrl(string $exam_url): StandartExam
    {
        return StandartExam::where('url', $exam_url)->first();
    }

    /**
     * Получить весь список вопросов для теста
     *
     * @param  string  $exam_id  Id-к теста
     * @return  Collection  Полный список его вопросов
     */
    public function getAllQuestionsForExam(string $exam_id): Collection
    {
        return StandartQuestion::where('exam_id', $exam_id)->get();
    }

    /**
     * Получаем нужный вопрос по его Id
     *
     * @param  string  $question_id  Идентификатор вопроса
     * @return  StandartQuestion  Объект вопроса
     */
    public function getQuestionById(string $question_id): StandartQuestion
    {
        return StandartQuestion::findOrFail($question_id);
    }

    /**
     * Получаем нужный тест по его Id
     *
     * @param  string  $exam_id  Идентификатор теста
     * @return  StandartExam  Объект теста
     */
    public function getExamById(string $exam_id): StandartExam
    {
        return StandartExam::findOrFail($exam_id);
    }
}
