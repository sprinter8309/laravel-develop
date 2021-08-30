<?php

namespace App\Services;

use App\Models\PostComment;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class UserService
{
    /**
     * Функция создания комментария для пользователя
     *
     * @param  User  Текущий пользователь
     * @param  Request  Содержание пришедшего запроса
     * @return  ?string  Если возвращаем строку - была ошибка, если null - все нормально добавилось (на этапе
     *                       получения данных от этой функции далее уже разбираем что делать)
     */
    public function addComment(?User $user, Post $post, Request $request): ?string
    {
        if (empty($user)) {
            return User::USER_NO_AUTH;
        }

        $request->validate([
            'comment'=>'required|min:2'
        ]);

        // Если были ошибки то до сюда ход программы не дойдет
        try {
            PostComment::create([
                'status'=> PostComment::STATUS_ACTIVE,
                'content'=>$request->comment,
                'post_id'=>$post->id,
                'user_id'=>$user->id
            ]);
        } catch (\Throwable $except) {
            return PostComment::COMMENT_ERROR_IN_ADD;
        }

        return null;
    }
}
