<?php

namespace Modules\Post\Entities;

use App\Components\BaseDto;

class SinglePostInfo extends BaseDto
{
    public $post;

    public $author;

    public $comments;

    public $error;

    public $message;
}
