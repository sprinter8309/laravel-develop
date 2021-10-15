<?php

namespace Modules\Post\Entities;

use App\Components\BaseDto;

/**
 * Класс DTO характера для передачи данных о выводимой статье (с комментариями и др)
 *
 * @author Oleg Pyatin
 */
class SinglePostInfo extends BaseDto
{
    public $post;

    public $author;

    public $comments;

    public $error;

    public $message;
}
