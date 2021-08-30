<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoryController extends Controller
{
    public const THEME_NAME = [
        1 => 'Путешествия',
        2 => 'Техника',
        3 => 'Кулинария',
        4 => 'Наука'
    ];

    public function posts(string $category_id)
    {
        $category_posts = Post::select(['title', 'preview', 'image', 'id'])
                                ->where('category_id', $category_id)
                                ->addSelect(['category'=>Category::select(['name'])
                                        ->whereColumn('post.category_id', 'id')->limit(1)])->get();

        return view('post.index', [
            'title'=>static::THEME_NAME[$category_id],
            'posts'=>$category_posts
        ]);
    }
}
