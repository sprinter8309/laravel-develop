<?php

namespace App\Components\Constants;

class UsersConstant
{
    /**
     * Активный статус
     */
    public const USER_ACTIVE_STATUS = 'active';
    /**
     * Статус для простого пользователя (не админа и пр.)
     */
    public const USER_BASIC_TYPE = 'user';
    /**
     * Случай когда пользователь не указывает соцсети
     */
    public const USER_EMPTY_SOCIAL_NETWORKS = '{}';
}
