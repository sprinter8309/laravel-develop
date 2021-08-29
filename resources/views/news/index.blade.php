@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('content')
    <div>
        <h2 class='content-title-label'>{{ $title ?? "Новости" }}</h2>

        <div class="news-section-first-container clearfix">
            <a href="{{ route('news.single', ['news_id'=>$first_news->id]) }}" class="news-section-first-item">
                <div class="news-section-first-item-image">
                    <img src="{{ asset($first_news->preview_image) }}">
                </div>
                <div class="news-section-first-item-title">
                    <h3>{{ $first_news->title }}</h3>
                </div>
                <div class="news-section-first-item-text">
                    <?php echo $first_news->preview_text; ?>
                </div>
            </a>
            <a href="{{ route('news.single', ['news_id'=>$second_news->id]) }}" class="news-section-second-item">
                <div class="news-section-second-item-image">
                    <img src="{{ asset($second_news->preview_image) }}">
                </div>
                <div class="news-section-second-item-title">
                    <h3>{{ $second_news->title }}</h3>
                </div>
                <div class="news-section-second-item-text">
                    <?php echo $second_news->preview_text; ?>
                </div>
            </a>
        </div>

        @foreach ($other_news as $news_item)
            <a href="{{ route('news.single', ['news_id'=>$news_item->id]) }}" class='news-list-item'>
                <div class="news-list-item-image">
                    <img src="{{ asset($news_item->preview_image) }}">
                </div>
                <div class="news-list-item-block">
                    <h3>{{ $news_item->title }}</h3>
                    <div class="news-list-item-date">
                        <?php echo date_create($news_item->created_at)->format("d.m.Y"); ?>
                    </div>
                    <div class="news-list-item-text">
                        <?php echo $news_item->preview_text; ?>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
