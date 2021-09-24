<?php

namespace Modules\User\Repositories;

use App\Models\User;

class UserRepository
{
    public function saveNewUser(User $user)
    {
        $user->saveOrFail();
    }
}
