<?php

namespace Modules\News\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Modules\News\Services\NewsService;

class NewsController extends BaseController
{
    public function __construct(NewsService $news_service)
    {
        $this->news_service = $news_service;
    }

    /**
     * Вывод полного списка новостей
     *
     * @return View   Страница со всеми новостями
     */
    public function news()
    {
        $news = $this->news_service->getAllNews();

        $first_news = $news->shift();
        $second_news = $news->shift();

        return view('news.index', [
            'first_news'=>$first_news,
            'second_news'=>$second_news,
            'other_news'=>$news
        ]);
    }

    /**
     * Вывод одиночной новости
     *
     * @param string $news_id   Id-к запрашиваемой новости
     * @return View   Страница одиночной новости
     */
    public function single(string $news_id)
    {
        return view('news.single', [
            'news_item'=>$this->news_service->getNewsById($news_id)
        ]);
    }
}