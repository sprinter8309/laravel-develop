<?php

namespace Modules\Exam\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\StandartQuestion;
use App\Models\StandartExam;
use App\Models\ExamAttempt;
use Carbon\Carbon;

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
        return StandartQuestion::where('exam_id', $exam_id)->orderBy('id')->get();
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

    /**
     * Получаем количество вопросов для заданного теста (используется в окошке вывода вопроса)
     *
     * @param  string  $exam_id  Идентификатор теста
     * @return  StandartExam  Объект теста
     */
    public function getQuestionsAmountForExam(string $exam_id): int
    {
        return StandartExam::findOrFail($exam_id)->getQuestionsAmount();
    }

    /**
     * Создание новой попытки прохождения экзамена (в БД, после запуска теста из превью)
     *
     * @param  ExamAttempt  $new_exam_attempt  Подготовленный в фабрике объект
     * @return  void  Просто сохраняем
     */
    public function saveNewExamAttempt(ExamAttempt $new_exam_attempt): bool
    {
        return $new_exam_attempt->save();
    }

    /**
     * Обновляем ответы пользователя которые будут храниться в БД
     *
     * @param int $attempt_id  ID попытки хранящейся в БД
     * @param array $updated_answers  Обновленный список ответов
     * @return void  Просто обновляем ничего не возвращаем
     */
    public function updateExamAttempt(int $attempt_id, array $updated_answers): void
    {
        $attempt = ExamAttempt::findOrFail($attempt_id);
        $attempt->user_answers = json_encode($updated_answers);
        $attempt->save();
    }

    /**
     * Делаем указание в БД что попытка завершена (при заврешении теста или проверке имеющихся незавершенных попыток)
     *
     * @param int $attempt_id  ID попытки хранящейся в БД
     * @return void  Просто помечаем как завершенную, ничего не возвращаем
     */
    public function finishExamAttempt(int $attempt_id)
    {
        $attempt = ExamAttempt::findOrFail($attempt_id);
        $attempt->status = ExamAttempt::FINISH_EXAM_STATUS;
        $attempt->finish_at = Carbon::now();
        $attempt->save();
    }
}
