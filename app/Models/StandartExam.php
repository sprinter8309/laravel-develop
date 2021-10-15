<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с объектом типового теста (не сгенерированного автоматически)
 *
 * @author Oleg Pyatin
 */
class StandartExam extends Model
{
    use HasFactory;

    protected $table = 'standart_exam';

    public static function getExamByUrl(string $exam_url): StandartExam
    {
        return static::where('url', $exam_url)->first();
    }

    public function category()
    {
        return $this->belongsTo(CategoryExam::class, 'category_exam_id');
    }

    public function questions()
    {
        return $this->hasMany(StandartQuestion::class, 'exam_id');
    }

    public function getQuestionsAmount()
    {
        return $this->hasMany(StandartQuestion::class, 'exam_id')->count();
    }

    public static function getNameById(string $exam_id)
    {
        return static::findOrFail($exam_id)->name;
    }
}
