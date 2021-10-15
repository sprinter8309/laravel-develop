<?php

namespace Modules\Exam\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Exam\Services\ExamService;
use Modules\Exam\Entities\QuestionAnswer;
use App\Helpers\ConvertHelper;

/**
 * Контроллер для выполнения действий с разделом тестов
 *
 * @author Oleg Pyatin
 */
class ExamController extends Controller
{
    public function __construct(ExamService $exam_service)
    {
        $this->exam_service = $exam_service;
    }

    /**
     * Вывод всех тестов (главное окно раздела)
     *
     * @return View
     */
    public function index()
    {
        return view('exam.index', [
            'pair_exams'=>$this->exam_service->getAllExams()
        ]);
    }

    /**
     * Действие входной страницы (превью) теста - отсюда начинаем его прохождение, или можем
     *     продолжить если начали ранее
     *
     * @param string $exam_url  URL нужного теста
     * @return View
     */
    public function preview(string $exam_url)
    {
        $preview_exam = $this->exam_service->getPreviewExam($exam_url);

        return view('exam.preview', [
            'id'=>$preview_exam->id,
            'name'=>$preview_exam->name,
            'description'=>$preview_exam->description,
            'point_value'=>$preview_exam->point_value,
            'time_limit'=>ConvertHelper::convertSecondsToTimeInterval($preview_exam->time_limit),
            'category_exam'=>$preview_exam->category->name,
            'questions_amount'=>$preview_exam->getQuestionsAmount(),
            'preview_img'=>$preview_exam->preview_img,
            'version'=>$preview_exam->version,
            'in_process'=>$this->exam_service->checkExamInProcess($preview_exam->id)
        ]);
    }

    /**
     * Действие старта теста (или продолжения), получаем все нужные данные о тесте и переходим
     *     к окну вопросов
     *
     * @param Request $request  Входной запрос
     * @param string $exam_id  ID экзамена
     * @return View
     */
    public function launchExam(Request $request, string $exam_id)
    {
        $question = $this->exam_service->launchExam($request, $exam_id);

        return view('exam.question', [
            'quest_id'=>$question->id,
            'quest_text'=>$question->quest_text,
            'quest_type'=>$question->quest_type,
            'quest_number'=>$this->exam_service->getCurrentNumberQuestion($exam_id, $question->id),
            'exam_id'=>$question->exam_id,
            'current_answer'=>$this->exam_service->getCurrentAnswerForQuestion($exam_id, $question->id),
            'answers'=>json_decode($question->answers, true),
            'answers_amount'=>$this->exam_service->getCurrentAnswersAmountForExam($exam_id),
            'total_question_amount'=>$this->exam_service->getQuestionsAmountForExam($exam_id)
        ]);
    }

    /**
     * Основное действие для перемещения внутри теста
     *
     * @param Request $request  Входной запрос
     * @param string $direction  Направление указанное в форме enum-слова
     * @return View
     */
    public function answerOnQuestion(Request $request, string $direction)
    {
        $answer = QuestionAnswer::getFromRequest($request);

        $this->exam_service->processAnswer($answer);

        $question = $this->exam_service->getQuestionDependingOnDirection($direction, $answer->exam_id);

        return view('exam.question', [
            'quest_id'=>$question->id,
            'quest_text'=>$question->quest_text,
            'quest_type'=>$question->quest_type,
            'quest_number'=>$this->exam_service->getCurrentNumberQuestion($answer->exam_id, $question->id),
            'exam_id'=>$question->exam_id,
            'current_answer'=>$this->exam_service->getCurrentAnswerForQuestion($answer->exam_id, $question->id),
            'answers'=>json_decode($question->answers, true),
            'answers_amount'=>$this->exam_service->getCurrentAnswersAmountForExam($answer->exam_id),
            'total_question_amount'=>$this->exam_service->getQuestionsAmountForExam($answer->exam_id)
        ]);
    }

    /**
     * Действие завершения начатого ранее теста (делаем расчет результатов, подчищаем использовавшиеся промежуточные средства)
     *
     * @param Request $request  Входной запрос
     * @return View
     */
    public function finishExam(Request $request)
    {
        $answer = QuestionAnswer::getFromRequest($request);
        $this->exam_service->processAnswer($answer);

        // Расчитывание сессии экзамена в соответствии с правилами
        $result = $this->exam_service->checkExamAnswers($answer);

        $this->exam_service->finishCurrentExamAttempt($answer);

        return view('exam.result', [
            'exam_name'=>$result->getExamName(),
            'right_answers_amount'=>$result->getRightAnswersAmount(),
            'questions_total_quantity'=>$result->getQuestionsTotalQuantity(),
            'mark'=>$result->getMark(),
            'user_answers'=>$result->getUserAnswers(),
            'right_answers_percent'=>$result->getRightAnswersPercent(),
            'exam_questions'=>$result->getExamQuestions(),
            'answers_show'=>$result->getAnswersShow(),
            'exam_right_answers_map'=>$result->getExamRightAnswersMap()
        ]);
    }
}
