<?php

namespace Modules\User\Repositories;

use App\Models\User;
use App\Models\ExamAttempt;
use App\Models\StandartExam;
use Illuminate\Database\Eloquent\Collection;

/**
 * Репозиторий для выполнения действий над пользователями в БД
 *
 * @author Oleg Pyatin
 */
class UserRepository
{
    /**
     * Функция сохранения нового пользователя (например при регистрации)
     *
     * @param  User  $user  Объект пользователя (создан перед этим на фабрике)
     * @return  void  Просто сохраняем
     */
    public function saveNewUser(User $user)
    {
        $user->saveOrFail();
    }

    /**
     * Получаем все попытки прохождения тестов у пользователя (включая те которые еще в процессе)
     *
     * @param  int  $user_id  ID текущего пользователя
     * @return  Collection  Полный список попыток
     */
    public function getUserExamAttemptsList(int $user_id): Collection
    {
        return ExamAttempt::addSelect(['exam_name'=>StandartExam::select(['name'])
                    ->whereColumn('exam_attempt.exam_id', 'id')->limit(1)])->where('user_id', $user_id)->get();
    }

    /**
     * Функция получения попытки теста у пользователя
     *
     * @param int $user_id  ID пользователя
     * @param string $attempt_id  ID попытки
     * @return ExamAttempt
     */
    public function getUserExamAttempt(int $user_id, string $attempt_id): ?ExamAttempt
    {
         return ExamAttempt::addSelect(['exam_name'=>StandartExam::select(['name'])
                                            ->whereColumn('exam_attempt.exam_id', 'id')->limit(1)])
                                ->where('user_id', $user_id)
                                ->where('id', $attempt_id)
                                ->first();
    }
}
