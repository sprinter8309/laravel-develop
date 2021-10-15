<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Modules\Post\Services\PostService;

/**
 * Контроллер организующий действия со статическими или одиночными (не в разделах) страницами сайта
 *
 * @author Oleg Pyatin
 */
class SiteController extends BaseController
{
    public function __construct(PostService $post_service)
    {
        $this->post_service = $post_service;
    }

    /**
     * Вывод главной страницы сайта (выводим все статьи)
     *
     * @return View
     */
    public function index()
    {
        return view('index', [
            'posts'=>$this->post_service->getPostsWithCategoryName()
        ]);
    }

    /**
     * Вывод страницы "О блоге"
     *
     * @return View
     */
    public function about()
    {
        return view('about');
    }
}