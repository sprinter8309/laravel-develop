<?php

namespace Modules\Post\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Repositories\PostRepository;
use Modules\Post\Entities\SinglePostInfo;
use App\Services\CommentService;
use App\Models\Author;
use App\Models\PostComment;

/*
 * Сервис организует логику вывода постов для приложения
 *
 * @author Oleg Pyatin
 */
class PostService
{
    public $user_service;

    public function __construct(CommentService $comment_service, PostRepository $post_repository)
    {
        $this->post_repository = $post_repository;
        $this->comment_service = $comment_service;
    }

    /**
     * Функция используемая для получения полного списка постов в главной странице - с присоединением информации о
     *     категории поста
     *
     * @return  Collection  Массив статей с присоединенными категориями
     */
    public function getPostsWithCategoryName()
    {
        return $this->post_repository->getPostsWithCategoryName();
    }

    /**
     * Функция используемая для получения одиночного поста и возможного добавления комментариев к нему
     *
     * @param  Request  Содержание пришедшего запроса
     * @param  string  Идентификатор поста
     *
     * @return  Collection  Массив статей с присоединенными категориями
     */
    public function getSinglePost(Request $request, string $post_id)
    {
        $post = $this->post_repository->getSinglePost($post_id);

        if ($request->isMethod('POST')) {

            $result = $this->comment_service->addComment(Auth::user(), $post, $request);

            // Если вернулся null - значит выводим что все хорошо, если нет выводим ошибку
            if (empty($result)) {
                $message = PostComment::COMMENT_SUCCESSFULL_ADD;
            } else {
                $error = $result;
            }
        }

        return SinglePostInfo::loadFromArray([
            'post'=>$post,
            'author'=>Author::findOrFail($post->author_id),
            'comments'=>PostComment::where('post_id', $post->id)->get(),
            'error'=>$error ?? null,
            'message'=>$message ?? null
        ]);
    }
}
