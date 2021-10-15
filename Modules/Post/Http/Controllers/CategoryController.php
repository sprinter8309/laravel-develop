<?php

namespace Modules\Post\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Modules\Post\Services\CategoryService;

/**
 * Контроллер организующий действия со статьями в аспекте их деления на категории
 *
 * @author Oleg Pyatin
 */
class CategoryController extends BaseController
{
    public function __construct(CategoryService $category_service)
    {
        $this->category_service = $category_service;
    }

    /**
     * Действие вывода всех статей для заданной категории
     *
     * @param string $category_id  ID категории
     * @return View
     */
    public function posts(string $category_id)
    {
        return view('post.index', [
            'title'=>$this->category_service->getCategoryName($category_id),
            'posts'=>$this->category_service->getAllCategoryPosts($category_id)
        ]);
    }
}
