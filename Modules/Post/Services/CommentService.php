<?php

namespace Modules\Post\Services;

//use App\Models\PostComment;
use Modules\Post\Factories\PostFactory;
use Modules\Post\Repositories\PostRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

/*
 * Сервис организует работу с пользователями приложения
 *
 * @author Oleg Pyatin
 */
class CommentService
{
    public function __construct(PostFactory $post_factory, PostRepository $post_repository)
    {
        $this->post_factory = $post_factory;
        $this->post_repository = $post_repository;
    }
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

        $new_post_comment = $this->post_factory->createPostComment($request->comment, $post->id);

        if (!$this->post_repository->saveNewPostComment($new_post_comment)) {
            return PostComment::COMMENT_ERROR_IN_ADD;
        }

        return null;
    }
}
