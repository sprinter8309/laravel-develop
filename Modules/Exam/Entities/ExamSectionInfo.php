<?php

namespace Modules\Exam\Entities;

/*
 * Общий объект для хранения данных о прохождении тестов пользователя в сессии
 *
 * @author Oleg Pyatin
 */
class ExamSectionInfo
{
    public const EXAMS_SESSION_VAR_NAME = 'exams_set';

    public $current_exams;

    public function __construct()
    {
        $this->current_exams = [];
    }

    public static function getInfoFromSession()
    {
        $new_info_set = new static();
        $new_info_set->current_exams = session(static::EXAMS_SESSION_VAR_NAME) ?? [];
        return $new_info_set;
    }

    public function updateExamSet()
    {
        session([static::EXAMS_SESSION_VAR_NAME=>$this->current_exams]);
    }

    public function addNewExamAttemptIfNoExist(ExamInfo $exam_info)
    {
        if ($this->checkIfNewExamAlreadyInPass($exam_info->exam_id)) {
            $this->current_exams[$exam_info->exam_id] = $exam_info;
            session([static::EXAMS_SESSION_VAR_NAME=>$this->current_exams]);
        }
    }

    public function checkIfNewExamAlreadyInPass(string $exam_id)
    {
        if (isset($this->current_exams[$exam_id])) {
            return false;
        }

        return true;
    }

    public function removeExamFromSession(string $exam_id)
    {
        unset($this->current_exams[$exam_id]);
        $this->updateExamSet();
    }
}
