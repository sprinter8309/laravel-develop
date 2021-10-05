<?php

namespace Modules\Exam\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Exam\Services\ExamService;
use App\Models\StandartExam;
use App\Models\StandartQuestion;
use Modules\Exam\Entities\QuestionAnswer;
use App\Helpers\ConvertHelper;

class ExamController extends Controller
{
    public function __construct(ExamService $exam_service)
    {
        $this->exam_service = $exam_service;
    }

    public function index()
    {
        return view('exam.index', [
            'pair_exams'=>$this->exam_service->getAllExams()
        ]);
    }

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
        ]);
    }

    public function beginStandartExam(string $exam_id)
    {
        $question = $this->exam_service->beginNewStandartExam($exam_id);

        return view('exam.question', [
            'exam_id'=>$exam_id,
            'quest_id'=>$question->id,
            'quest_text'=>$question->quest_text,
            'quest_type'=>$question->quest_type,
            'quest_number'=>StandartQuestion::FIRST_QUESTION_NUMBER,
            'current_answer'=>null,
            'answers'=>json_decode($question->answers, true),
            'answers_amount'=> StandartExam::BEGIN_ANSWERS_QUANTITY,
            'total_question_amount'=>StandartExam::getQuestionsAmountForExam($exam_id)
        ]);
    }

    // Функция ответа на вопрос (также используется и для любого перехода внутри теста)
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
            'total_question_amount'=>StandartExam::getQuestionsAmountForExam($answer->exam_id)
        ]);
    }


    // Функция завершения теста
    public function finishExam(Request $request)
    {
        // При этом возможно последнее заполнение
        $answer = QuestionAnswer::getFromRequest($request);
        $this->exam_service->processAnswer($answer);

        // Расчитывание сессии экзамена в соответствии с правилами
        $result = $this->exam_service->checkExamAnswers($answer);

        // Удаление экзамена из списка экзаменов в сессии
        $this->exam_service->finishCurrentExamAttempt($answer);

        // Вывод представления
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

    // Технические действия для разработки (при коммите удалить (вместе с маршрутом))
    public function technic()
    {
        session()->flush();
    }
}
