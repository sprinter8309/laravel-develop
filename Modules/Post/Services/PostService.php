<?php

namespace Modules\Post\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Repositories\PostRepository;
use Modules\Post\Factories\PostFactory;
use Modules\Post\Entities\SinglePostInfo;
use Modules\Post\Entities\EditPostInfo;
use Modules\Post\Services\CommentService;
use Modules\Post\Services\CategoryService;
use Modules\Post\Http\Requests\PostCreateRequest;
use Modules\Post\Http\Requests\PostUpdateRequest;
use App\Models\Author;
use App\Models\Post;
use App\Models\PostComment;
use App\Components\Constants\FilesConstant;
use App\Components\Constants\PostsConstant;


/**
 * Сервис организует логику вывода постов для приложения
 *
 * @author Oleg Pyatin
 */
class PostService
{
    public $user_service;
    
    public $category_service;

    public function __construct(CommentService $comment_service, PostRepository $post_repository, PostFactory $post_factory,
                                CategoryService $category_service)
    {
        $this->post_repository = $post_repository;
        $this->post_factory = $post_factory;
        $this->comment_service = $comment_service;
        $this->category_service = $category_service;
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

    /**
     * Функция попытки создания новой статьи (валидируем, сохраняем изображение и заносим в БД)
     *
     * @param PostCreateRequest $request
     * @return View
     */
    public function createNewPost(PostCreateRequest $request)
    {
        $preview_image_path = FilesConstant::SYMLINK_PATH . $request->file('image')
                                    ->store(FilesConstant::PREVIEW_FOLDER, FilesConstant::LOCAL_STORAGE);

        $new_post = $this->post_factory->createNewPost(EditPostInfo::loadFromArray([
            'title'=>$request->post('title'),
            'preview'=>$request->post('preview'),
            'content'=>$request->post('content'),
            'image'=>$preview_image_path,
            'category_id'=>$request->post('category')
        ]));

        return $this->post_repository->saveNewPost($new_post);
    }

    /**
     * Функция удаления статьи
     *
     * @param string $delete_post_id  ID удаляемой статьи
     * @return type
     */
    public function deletePost(string $delete_post_id): bool
    {
        return $this->post_repository->deletePost($delete_post_id);
    }

    /**
     * Простое получение статьи по ID (для внешнего сервиса)
     *
     * @param string $post_id  ID поста
     * @return type
     */
    public function getPostById(string $post_id): Post
    {
        return $this->post_repository->getSinglePost($post_id);
    }

    /**
     * Функция редактирования статьи (дополнительно проверяем наличие подгруженного файла, если он есть
     *     заменяем превью изображения)
     *
     * @param PostUpdateRequest $request  Запрос с отредактированными данными (на этом этапе уже провалидирован)
     * @return type
     */
    public function editPost(PostUpdateRequest $request)
    {
        if ($request->hasFile('image')) {
            $updated_file = FilesConstant::SYMLINK_PATH . $request->file('image')
                                    ->store(FilesConstant::PREVIEW_FOLDER, FilesConstant::LOCAL_STORAGE);
        }

        // Заносим в репозиторий, еще проверяем есть ли файл
        return $this->post_repository->editPost($request->post('id'), EditPostInfo::loadFromArray([
            'title'=>$request->post('title'),
            'preview'=>$request->post('preview'),
            'content'=>$request->post('content'),
            'image'=>$updated_file ?? null,
            'category_id'=>$request->post('category')
        ]));
    }
    
    /**
     * Функция получения нескольких случайных статей из категории рассматриваемой статьи (нужна для блока 
     *     статей по теме)
     *
     * @param Post $post  Модель статьи
     * @return Collection
     */
    public function getRandomSectionPosts(Post $post)
    {       
        $category_posts = $this->category_service->getAllCategoryPosts($post->section->id);
        
        $other_category_posts = $category_posts->filter(function ($category_post, $key) use ($post) {
            return $category_post->id !== $post->id;
        });
        
        $other_category_posts_quantity = $other_category_posts->count();
        
        $random_posts_quantity = ($other_category_posts_quantity <= PostsConstant::MAX_RANDOM_SECTION_POSTS) ? $other_category_posts_quantity : PostsConstant::MAX_RANDOM_SECTION_POSTS;
        
        return $other_category_posts->random($random_posts_quantity);
    }
}
