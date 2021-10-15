<?php

namespace Modules\Exam\Factories;

use App\Models\ExamAttempt;
use App\Models\StandartExam;
use Illuminate\Support\Facades\Auth;

/*
 * Класс-фабрика для создания и сохранения объектов работы с тестами
 *
 * @author Oleg Pyatin
 */
class ExamFactory
{
    public function createExamAttempt(StandartExam $exam, array $user_answers): ExamAttempt
    {
        $new_exam_attempt = new ExamAttempt();
        $new_exam_attempt->exam_id = $exam->id;
        $new_exam_attempt->user_id = Auth::user()->id ?? null;
        $new_exam_attempt->exam_version = $exam->version;
        $new_exam_attempt->status = ExamAttempt::PROCESS_EXAM_STATUS;
        $new_exam_attempt->user_answers = json_encode($user_answers);
        $new_exam_attempt->finish_at = null;
        return $new_exam_attempt;
    }
}
