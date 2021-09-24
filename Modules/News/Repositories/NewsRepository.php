<?php

namespace Modules\News\Repositories;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository
{
    public function getAllNews(): Collection
    {
        return News::select(['id', 'title', 'preview_text', 'preview_image', 'status', 'author_id', 'created_at'])->get();
    }

    public function getNewsById(string $news_id): News
    {
        if ($news = News::findOrFail($news_id)) {
            return $news;
        } else {
            throw new Exception("Required news not exist");
        }
    }
}
