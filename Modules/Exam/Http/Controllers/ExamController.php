<?php

namespace Modules\Exam\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Exam\Services\ExamService;

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
}
