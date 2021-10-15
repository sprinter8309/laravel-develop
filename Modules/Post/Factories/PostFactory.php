<?php

namespace Modules\Post\Factories;

use App\Models\Post;
use App\Models\PostComment;
use Modules\Post\Entities\EditPostInfo;
use Illuminate\Support\Facades\Auth;

/*
 * Класс-фабрика для создания объектов статей и комментариев
 *
 * @author Oleg Pyatin
 */
class PostFactory
{
    /**
     * Создание комментария к статье
     *
     * @param string $content  Текст комментария
     * @param int $post_id  ID статьи
     * @return PostComment  Новый объект статьи
     */
    public function createPostComment(string $content, int $post_id): PostComment
    {
        $new_post_comment = new PostComment();
        $new_post_comment->status = PostComment::STATUS_ACTIVE;
        $new_post_comment->content = $content;
        $new_post_comment->post_id = $post_id;
        $new_post_comment->user_id = Auth::user()->id ?? null;
        return $new_post_comment;
    }

    public function createNewPost(EditPostInfo $create_info): Post
    {
//        dd($create_info);

        $new_post = new Post();
        $new_post->title = $create_info->title;
        $new_post->preview = $create_info->preview;
        $new_post->content = $create_info->content;
        $new_post->status = Post::STATUS_ACTIVE;
        $new_post->image = $create_info->image ?? '';
        $new_post->category_id = $create_info->category_id;
//        $new_post->author_id = 1;
        $new_post->author_id = Auth::user()->author->id;
        return $new_post;
    }
}
