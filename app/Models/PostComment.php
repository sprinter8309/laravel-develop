<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PostComment extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const COMMENT_SUCCESSFULL_ADD = 'Комментарий успешно добавлен';

    public const COMMENT_ERROR_IN_ADD = 'Возникла ошибка при добавлении комментария';

    protected $table = 'post_comment';

    protected $fillable = [
        'status',
        'content',
        'post_id',
        'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
