<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с объектами новостей
 *
 * @author Oleg Pyatin
 */
class News extends Model
{
    use HasFactory;

    protected $table = 'news';
}
