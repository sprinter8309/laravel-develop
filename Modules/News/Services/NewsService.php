<?php

namespace Modules\News\Services;

use App\Models\News;
use Modules\News\Repositories\NewsRepository;

/*
 * Сервис организует логику работы с разделом новостей
 *
 * @author Oleg Pyatin
 */
class NewsService
{
    public function __construct(NewsRepository $news_repository)
    {
        $this->news_repository = $news_repository;
    }

    /**
     * Функция получения всех новостей имеющихся на сайте
     */
    public function getAllNews()
    {
        return $this->news_repository->getAllNews();
    }

    /**
     * Функция получения одной новости по id-ку
     */
    public function getNewsById(string $news_id): News
    {
        return $this->news_repository->getNewsById($news_id);
    }
}
