<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Services\AdminService;
use App\Components\GridViewProvider;
use Modules\Post\Http\Requests\PostCreateRequest;
use Modules\Post\Http\Requests\PostUpdateRequest;
use App\Models\Post;

class AdminController extends Controller
{
    public function __construct(AdminService $admin_service)
    {
        $this->admin_service = $admin_service;
    }

    /**
     * Вывод главной страницы админки
     *
     * @return type
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Функция вывода страницы со статьями
     *
     * @param  Request  $request  Входящий запрос с параметрами (если там GET-параметры будет рассматриваться как AJAX)
     * @return View
     */
    public function posts(Request $request)
    {
        $posts_index_data_provider = GridViewProvider::getDataProvider($this->admin_service->getPostsList(), $request);

        if ($posts_index_data_provider->checkAjaxMode()) {
            return view('admin.posts_index_grid', [
                'post_index_data_provider'=>$posts_index_data_provider
            ]);
        } else {
            return view('admin.posts_index', [
                'post_index_data_provider'=>$posts_index_data_provider
            ]);
        }
    }

    /**
     * Действие вывода на экран формы создания статьи
     *
     * @return View
     */
    public function createPost(Request $request)
    {
        $this->admin_service->checkPolicy($request, 'create', Post::class);

        return view('admin.edit', [
            'categories'=>$this->admin_service->getPostCategories(),
            'action_type'=>Post::POST_CREATE
        ]);
    }

    /**
     * Действие попытки создания статьи, если данные корректные данные корректные то создаем и переводим
     *     на страницу со списком стстаей, если нет - выдаем форму создания с указанием предыдущих ошибок
     *
     * @param PostCreateRequest $request  Входящий запрос для которого мы выполняем валидацию
     * @return View
     */
    public function storePost(PostCreateRequest $request)
    {
        $this->admin_service->checkPolicy($request, 'create', Post::class);

        $this->admin_service->createNewPost($request);

        $posts_index_data_provider = GridViewProvider::getDataProvider($this->admin_service->getPostsList(), $request);

        return view('admin.posts_index', [
            'post_index_data_provider'=>$posts_index_data_provider,
            'success_message'=>Post::POST_SUCCESS_ADD
        ]);
    }

    /**
     * Функция вывода на экран окна редактирования статьи
     *
     * @param string $update_post_id
     * @return type
     */
    public function updatePost(Request $request, string $update_post_id)
    {
        $this->admin_service->checkPolicy($request, 'update', $post = $this->admin_service->getPostById($update_post_id));

        return view('admin.edit', [
            'categories'=>$this->admin_service->getPostCategories(),
            'update_data'=>$this->admin_service->prepareDataFromPost($post),
            'id'=>$update_post_id,
            'action_type'=>Post::POST_UPDATE
        ]);
    }

    /**
     * Функция попытки редактирования статьи (проверяем насколько данные корректные и либо редиректим на исправление ошибок либо
     *     если все норм сохраняем и выводим список статей с сообщеним об успешном редактировании)
     *
     * @param PostUpdateRequest $request
     * @return type
     */
    public function editPost(PostUpdateRequest $request)
    {
        $this->admin_service->checkPolicy($request, 'update', $post = $this->admin_service->getPostById($request->post('id')));

        $this->admin_service->editPost($request);

        $posts_index_data_provider = GridViewProvider::getDataProvider($this->admin_service->getPostsList(), $request);

        return view('admin.posts_index', [
            'post_index_data_provider'=>$posts_index_data_provider,
            'success_message'=>Post::POST_SUCCESS_UPDATE
        ]);
    }

    /**
     * Функция удаления статьи - пробуем удалить (soft delete) и выводим результат попытки
     *
     * @param Request $request  Инжектируем входной запрос для использование Simple Grid View
     * @param string $delete_post_id  ID удаляемой статьи
     * @return View
     */
    public function deletePost(Request $request, string $delete_post_id)
    {
        $this->admin_service->checkPolicy($request, 'delete', $post = $this->admin_service->getPostById($delete_post_id));

        $message = $this->admin_service->deletePost($delete_post_id);

        $posts_index_data_provider = GridViewProvider::getDataProvider($this->admin_service->getPostsList(), $request);

        return view('admin.posts_index', [
            'post_index_data_provider'=>$posts_index_data_provider,
            'success_message'=>$message ?? null,
            'error'=>(empty($message)) ? Post::POST_ERROR_DELETE : null,
        ]);
    }
}
