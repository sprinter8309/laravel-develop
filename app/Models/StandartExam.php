<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandartExam extends Model
{
    use HasFactory;

    protected $table = 'standart_exam';

    public const BEGIN_ANSWERS_QUANTITY = '0';

    public static function getExamByUrl(string $exam_url): StandartExam
    {
        return static::where('url', $exam_url)->first();
    }

    public function category()
    {
        return $this->belongsTo(CategoryExam::class, 'category_exam_id');
    }

    public function getQuestionsAmount()
    {
        return $this->hasMany(StandartQuestion::class, 'exam_id')->count();
    }

    public static function getQuestionsAmountForExam(string $exam_id):int
    {
        return static::findOrFail($exam_id)->getQuestionsAmount();
    }
}
