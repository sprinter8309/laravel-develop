<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с категориями тестов
 *
 * @author Oleg Pyatin
 */
class CategoryExam extends Model
{
    use HasFactory;

    protected $table = 'category_exam';
}
