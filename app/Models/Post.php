<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель для работы с объектами статей
 *
 * @author Oleg Pyatin
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    /**
     * Статус для обозначения активной статьи
     */
    public const STATUS_ACTIVE = 'active';
    /**
     * Сообщение при успешном добавлении статьи
     */
    public const POST_SUCCESS_ADD = "Статья успешно добавлена в БД";
    /**
     * Сообщение при успешном редактировании статьи
     */
    public const POST_SUCCESS_UPDATE = "Правки в статью успешно внесены";
    /**
     * Сообщение при успешном удалении статьи
     */
    public const POST_SUCCESS_DELETE = "Статья успешно удалена";
    /**
     * Сообщение при ошибке в удалении статьи
     */
    public const POST_ERROR_DELETE = "При попытке удаления возникли ошибки";
    /**
     * Обозначение обновления статьи
     */
    public const POST_UPDATE = 'update';
    /**
     * Обозначение создания статьи
     */
    public const POST_CREATE = 'create';
    /**
     * Получение раздела для рассматриваемой статьи
     */
    public function section()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
