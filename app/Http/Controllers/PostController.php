<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Post;
use App\Models\Author;
use App\Models\Category;
use App\Models\PostComment;

class PostController extends BaseController
{
    public function posts()
    {
        $posts = Post::select(['title', 'preview', 'image', 'id'])
                        ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();

        return view('post.index', [
            'posts'=>$posts
        ]);
    }

    public function news()
    {
        return view('post.news');
    }

    public function single(string $post_id)
    {
        $post = Post::findOrFail($post_id);

        $author = Author::findOrFail($post->author_id);

        $comments = PostComment::where('post_id', $post->id)->get();

        return view('post.single', [
            'post'=>$post,
            'author'=>$author,
            'comments'=>$comments,
        ]);
    }
}
