<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\News;

class NewsController extends BaseController
{

    public function news()
    {
        $news = News::select(['id', 'title', 'preview_text', 'preview_image', 'status', 'author_id', 'created_at'])->get();

        $first_news = $news->shift();
        $second_news = $news->shift();

        return view('news.index', [
            'first_news'=>$first_news,
            'second_news'=>$second_news,
            'other_news'=>$news
        ]);
    }

    public function single(string $news_id)
    {
        $news_item = News::findOrFail($news_id);

        return view('news.single', [
            'news_item'=>$news_item
        ]);
    }
}
