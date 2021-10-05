<?php

namespace Modules\Exam\Http\Middleware;

use Modules\Exam\Entities\QuestionAnswer;

use App\Models\StandartExam;
use Modules\Exam\Entities\ExamSectionInfo;
use Illuminate\Support\Arr;
use Closure;

/*
 * Middleware используемая для проверки технической корректности приходящего ответа на вопрос
 *
 * @author Oleg Pyatin
 */
class CheckUserAnswer
{

    public function handle($request, Closure $next)
    {
        $answer = QuestionAnswer::getFromRequest($request);

        // Учитываем что вкладка могла быть просроченной, уже потенциально из завершенной попытки и пр
        if (!$this->isAttemptActual($answer)) {

            $query_exam = StandartExam::findOrFail($answer->exam_id);

            // Пробуем получить url экзамена из ошибочного запроса и отправить на его preview
            if (!empty($query_exam)) {
                return redirect(route('exam.preview', ['exam_url'=>$query_exam->url]));
            } else {
                return redirect('exam');
            }
        }

        return $next($request);
    }

    public function isAttemptActual(QuestionAnswer $answer): bool
    {
        $exams_set = ExamSectionInfo::getInfoFromSession();

        // Функция для проверки есть ли такой id экзамена в сессии, в дальнейшем здесь нужно все переводить на попытки Attempt
             // И если результат здесь отрицательный нужно выполнять блокирование работы
        if (Arr::has($exams_set->current_exams, $answer->exam_id)) {
            return true;
        }

        return false;
    }
}
