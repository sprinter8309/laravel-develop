<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use App\Models\UserAdmin;

/**
 * Модель для работы с объектами пользователей
 *
 * @author Oleg Pyatin
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const USER_NO_AUTH = 'Требуется аутентификация';

    /**
     * Обозначение уровня прав контент-менеджера
     */
    public const USER_STATUS_MANAGER = 'content-manager';
    /**
     * Обозначение уровня прав админа
     */
    public const USER_STATUS_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'user_type',
        'social_networks'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->hasOne(UserAdmin::class, 'user_id');
    }

    public function author()
    {
        return $this->hasOne(Author::class, 'user_id');
    }
}
