<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandartQuestion extends Model
{
    use HasFactory;

    protected $table = 'standart_question';

    public const FIRST_QUESTION_NUMBER = 1;

    public const SINGLE_CHOICE_QUEST_TYPE = 'single_choice';

    public const NEXT_QUESTION_MOVE = "next";

    public const PREVIOUS_QUESTION_MOVE = "previous";

    public const BEGIN_QUESTION_MOVE = "begin";
}
