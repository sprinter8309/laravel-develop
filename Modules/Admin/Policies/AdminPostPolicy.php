<?php

namespace Modules\Admin\Policies;

use App\Models\Post;
use App\Models\User;

/*
 * Класс-политика для обеспечения контроля доступа к изменению ресурсов в БД (кто может
 *     создавать/редактировать/удалять)
 *
 * @author Oleg Pyatin
 */
class AdminPostPolicy
{
    /**
     * Проверка на возможность действия создания (нужно иметь запись в таблице авторов (из нее возьмем ID) и права
     *     по типу контент-менеджера или админа)
     *
     * @param User $user  Инжектируется текущий пользователь
     * @return bool
     */
    public function create(User $user): bool
    {
        return ($user->author!==null
                && ($user->admin->status===User::USER_STATUS_MANAGER
                        || $user->admin->status===User::USER_STATUS_ADMIN));
    }

    /**
     * Проверка на возможность действия редактирования (нужно быть либо автором статьи, либо иметь статус админа)
     *
     * @param User $user  Инжектируется текущий пользователь
     * @param Post $post  Предполагаемый пост редактирования
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        return (($user->author->id ?? null) === $post->author_id || $user->admin->status===User::USER_STATUS_ADMIN);
    }

    /**
     * Проверка на возможность действия удаления (доступно только с правами админа)
     *
     * @param User $user  Инжектируется текущий пользователь
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->admin->status===User::USER_STATUS_ADMIN;
    }
}
