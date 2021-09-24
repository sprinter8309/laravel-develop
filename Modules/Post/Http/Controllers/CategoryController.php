<?php

namespace Modules\Post\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Modules\Post\Services\CategoryService;

class CategoryController extends BaseController
{
    public const THEME_NAME = [
        1 => 'Путешествия',
        2 => 'Техника',
        3 => 'Кулинария',
        4 => 'Наука'
    ];

    public function __construct(CategoryService $category_service)
    {
        $this->category_service = $category_service;
    }

    public function posts(string $category_id)
    {
        return view('post.index', [
            'title'=>static::THEME_NAME[$category_id],
            'posts'=>$this->category_service->getAllCategoryPosts($category_id)
        ]);
    }
}
