<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 1 Hexlet Код

class Article extends Model
{
    //
    protected $fillable = ['name', 'body'];
}
