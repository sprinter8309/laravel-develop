<?php

namespace Modules\Admin\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Modules\Post\Services\PostService;
use Modules\Post\Services\CategoryService;
use Modules\Post\Http\Requests\PostCreateRequest;
use Modules\Post\Http\Requests\PostUpdateRequest;
use Modules\Post\Entities\EditPostInfo;
use App\Models\Post;

/*
 * Сервис организует работу с функциональностью админки (подключение сервисов из других модулей для выполнения задач)
 *
 * @author Oleg Pyatin
 */
class AdminService
{
    public function __construct(PostService $post_service, CategoryService $category_service)
    {
        $this->post_service = $post_service;
        $this->category_service = $category_service;
    }

    /**
     * Функция получения списка всех статей с добавлением значения категории
     *
     * @return Collection
     */
    public function getPostsList(): Collection
    {
        return $this->post_service->getPostsWithCategoryName();
    }

    /**
     * Функция создания новой статьи (вызываем логику в стороннем сервисе)
     *
     * @param PostCreateRequest $request
     */
    public function createNewPost(PostCreateRequest $request)
    {
        $this->post_service->createNewPost($request);
    }

    /**
     * Функция получения всех имеющихся категорий статей (для их списка в создании/редактировании постов)
     *
     * @return array
     */
    public function getPostCategories(): array
    {
        return $this->category_service->getAllCategories()->toArray();
    }

    /**
     * Функция удаления статьи
     *
     * @param string $delete_post_id ID удаляемой статьи
     * @return string
     */
    public function deletePost(string $delete_post_id): string
    {
        if ($this->post_service->deletePost($delete_post_id)) {
            return Post::POST_SUCCESS_DELETE;
        } else {
            return null;
        }
    }

    /**
     * Простое получение статьи по ее ID (используем модуль статей)
     *
     * @param  string  $update_post_id  ID статьи
     * @return  Post  Полученный объект статьи
     */
    public function getPostById(string $update_post_id): Post
    {
        return $this->post_service->getPostById($update_post_id);
    }

    /**
     * Подготавливаем данные из статьи в DTO (нужно для зваполнения полей при редактировании)
     *
     * @param Post $post  Объект статьи
     * @return EditPostInfo  DTO с потенциально изменяемыми данными
     */
    public function prepareDataFromPost(Post $post): EditPostInfo
    {
        return EditPostInfo::loadFromArray([
            'title'=>$post->title,
            'preview'=>$post->preview,
            'content'=>$post->content,
            'image'=>$post->image,
            'category_id'=>$post->category_id
        ]);
    }

    /**
     * Функция редактирования статьи (передаем действия в сторонний сервис модуля статей)
     *
     * @param PostUpdateRequest  $request  Входной запрос с отредактированными данными
     * @return type
     */
    public function editPost(PostUpdateRequest $request)
    {
        return $this->post_service->editPost($request);
    }

    /**
     * Функция-обертка простой проверки на права (Используем чтобы упростить код контроллера)
     *
     * @param  Request  $request  Входной запрос
     * @param  string  $policy_action_name  Имя запрашиваемого действия для проверки
     * @param  type  $check_object  Обхект проверки (конкретный объект или имя класса для действий без объекта)
     */
    public function checkPolicy(Request $request, string $policy_action_name, $check_object): void
    {
        if ($request->user()->cannot($policy_action_name, $check_object)) {
            abort(403);
        }
    }
}
