<?php

namespace Modules\Post\Entities;

use App\Components\BaseDto;

/**
 * Класс DTO характера для передачи данных при создании новой статьи
 *
 * @author Oleg Pyatin
 */
class EditPostInfo extends BaseDto
{
    public $title;

    public $preview;

    public $content;

    public $image;

    public $category_id;
}
