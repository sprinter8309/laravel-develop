<?php

namespace Modules\News\Repositories;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

/**
 * Класс-репозиторий для выполнения действий с новостями
 *
 * @author Oleg Pyatin
 */
class NewsRepository
{
    /**
     * Получить полный список новостей
     *
     * @return Collection  Все новости с нужными полями
     */
    public function getAllNews(): Collection
    {
        return News::select(['id', 'title', 'preview_text', 'preview_image', 'status', 'author_id', 'created_at'])->get();
    }

    /**
     * Получить новость по ее ID-ку
     *
     * @param string $news_id  ID запрашиваемой новости
     * @return News  Объект новости
     * @throws Exception  Случай когда не получилось что-нибудь найти
     */
    public function getNewsById(string $news_id): News
    {
        if ($news = News::findOrFail($news_id)) {
            return $news;
        } else {
            throw new Exception("Запрошенная новость не найдена");
        }
    }
}
