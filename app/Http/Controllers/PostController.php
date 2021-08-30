<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Author;
use App\Models\Category;
use App\Models\PostComment;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    public $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function posts()
    {
        $posts = Post::select(['title', 'preview', 'image', 'id'])
                        ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();

        return view('post.index', [
            'posts'=>$posts
        ]);
    }

    public function single(Request $request, string $post_id)
    {
        $post = Post::findOrFail($post_id);

        if ($request->isMethod('POST')) {

            $result = $this->user_service->addComment(Auth::user(), $post, $request);

            // Если вернулся null - значит выводжим что все хорошо, если нет выводим ошибку
            if (empty($result)) {
                $message = PostComment::COMMENT_SUCCESSFULL_ADD;
            } else {
                $error = $result;
            }
        }

        return view('post.single', [
            'post'=>$post,
            'author'=>Author::findOrFail($post->author_id),
            'comments'=>PostComment::where('post_id', $post->id)->get(),
            'error'=>$error ?? null,
            'message'=>$message ?? null
        ]);
    }
}
