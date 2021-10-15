<?php

namespace Modules\User\Components;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Components\Constants\UsersConstant;

/*
 * Класс-фабрика для создания новых пользователей и объектов с ними связанных (например при регистрации)
 *
 * @author Oleg Pyatin
 */
class UserFactory
{
    public function create(array $data): User
    {
        $new_user = new User();

        $new_user->name = $data['name'];
        $new_user->email = $data['email'];
        $new_user->password = Hash::make($data['password']);
        $new_user->status = UsersConstant::USER_ACTIVE_STATUS;
        $new_user->user_type = UsersConstant::USER_BASIC_TYPE;
        $new_user->social_networks = UsersConstant::USER_EMPTY_SOCIAL_NETWORKS;

        return $new_user;
    }
}
