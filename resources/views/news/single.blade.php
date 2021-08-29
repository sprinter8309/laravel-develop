@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('content')
    <div class="content-block">
        <h2 class='content-title-label'><?php echo $news_item->title; ?></h2>
        <h4 class='content-breadcrumbs'>Хлебные крошки</h4>
        <div class="news-single-image">
            <img src="{{ asset($news_item->main_image) }}">
        </div>
        <div class="post-single-content">
            <?php echo $news_item->detail_text; ?>
        </div>
        <div class="post-single-info">
            Добавлено: <?php echo date_create($news_item->created_at)->format("d.m.Y"); ?>
        </div>
    </div>
@endsection
