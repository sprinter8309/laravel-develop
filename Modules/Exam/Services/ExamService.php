<?php

namespace Modules\Exam\Services;

use App\Models\StandartExam;

/*
 * Сервис организует логику работы с разделом тестов
 *
 * @author Oleg Pyatin
 */
class ExamService
{
    public function getAllExams()
    {
        $exam_set = StandartExam::select(['id', 'name', 'version', 'exam_category_id', 'time_limit', 'point_value', 'author_id', 'preview_img'])->get();

        $pair_exam_set = [];

        for ($i=0; $i<$exam_set->count(); $i+=2) {
            $pair_exam_set[] = [
                'left'=>$exam_set[$i] ?? null,
                'right'=>$exam_set[$i+1] ?? null
            ];
        }

        return $pair_exam_set;
    }
}
