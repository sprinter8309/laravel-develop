<?php

namespace Modules\Post\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Modules\Post\Services\PostService;

class PostController extends BaseController
{
    public function __construct(PostService $post_service)
    {
        $this->post_service = $post_service;
    }

    /**
     * Вывод страницы со всеми статьями блога
     */
    public function posts()
    {
        return view('post.index', [
            'posts'=>$this->post_service->getPostsWithCategoryName()
        ]);
    }

    public function single(Request $request, string $post_id)
    {
        $post_info = $this->post_service->getSinglePost($request, $post_id);

        return view('post.single', [
            'post'=>$post_info->post,
            'author'=>$post_info->author,
            'comments'=>$post_info->comments,
            'error'=>$post_info->error,
            'message'=>$post_info->message
        ]);
    }
}
