<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Post;

class MainController extends BaseController
{
    public function index()
    {
        $post = Post::all();
        return view('index', [
            'posts'=>$post
        ]);
    }

    public function about()
    {
        return view('about');
    }
}
