<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с категориями статей (наука, кулинария и пр)
 *
 * @author Oleg Pyatin
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
