<?php

namespace Modules\Post\Repositories;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Modules\Post\Entities\EditPostInfo;

/**
 * Класс-репозиторий для выполнения общих запросов к статьям
 *
 * @author Oleg Pyatin
 */
class PostRepository
{
    /**
     * Получение всех статей (любой категории, любых тегов и пр) с добавлением имени категории
     * @return Collection
     */
    public function getPostsWithCategoryName(): Collection
    {
        return Post::select(['title', 'preview', 'image', 'id'])
                        ->where('is_delete', 'f')
                        ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();
    }

    /**
     * Получение конкретной статьи
     *
     * @param string $post_id  ID статьи
     * @return Post  Объект статьи
     */
    public function getSinglePost(string $post_id): Post
    {
        return Post::where('is_delete', 'f')->findOrFail($post_id);
    }

    /**
     * Создание нового комментария к статье
     *
     * @param PostComment $new_post_comment  Объект комментария созданный через фабрику
     * @return bool  Возвращаем успешность сохранения
     */
    public function saveNewPostComment(PostComment $new_post_comment): bool
    {
        return $new_post_comment->save();
    }

    /**
     * Сохранение новой статьи
     *
     * @param Post $new_post  Объект сохраняемой статьи
     * @return bool  Возвращаем успешно ли сохранилось
     */
    public function saveNewPost(Post $new_post): bool
    {
        return $new_post->save();
    }

    /**
     * Удаляем статью (в мягком варианте) если таковая имеется и подходит, возвращаем успешность действия
     *
     * @param string $delete_post_id  ID удаляемой статьи
     * @return bool Результат действия
     */
    public function deletePost(string $delete_post_id): bool
    {
        $delete_post = Post::where('is_delete', 'f')->findOrFail($delete_post_id);

        if (!empty($delete_post)) {

            $delete_post->is_delete = 't';
            return $delete_post->save();
        }

        return false;
    }

    /**
     * Функция для редактирования статью, возвращаем успешность действия
     *
     * @param EditPostInfo $edit_post_info  Данные нужные для редактирования
     * @return bool
     */
    public function editPost(string $edit_post_id, EditPostInfo $edit_post_info): bool
    {
        $edit_post = Post::where('is_delete', 'f')->findOrFail($edit_post_id);

        if (!empty($edit_post)) {

            $edit_post->title = $edit_post_info->title;
            $edit_post->preview = $edit_post_info->preview;
            $edit_post->content = $edit_post_info->content;
            $edit_post->category_id = $edit_post_info->category_id;

            if (!empty($edit_post_info->image)) {
                $edit_post->image = $edit_post_info->image;
            }

            return $edit_post->save();
        }

        return false;
    }
}
