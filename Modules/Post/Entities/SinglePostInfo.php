<?php

namespace Modules\Post\Entities;

use App\Components\AbstractDto;

class SinglePostInfo extends AbstractDto
{
    public $post;

    public $author;

    public $comments;

    public $error;

    public $message;
}
