@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('content')
    <div class="content-block">
        <h2 class='content-title-label'><?php echo $post->title; ?></h2>
        <h4 class='content-breadcrumbs'>Хлебные крошки</h4>
        <div class="post-single-content">
            <?php echo $post->content; ?>
        </div>
        <div class="post-single-info">
            <div class="post-single-author">Автор: <?php echo $author->name . ' ' . $author->lastname; ?></div>
            Добавлено: <?php echo date_create($post->created_at)->format("d.m.Y"); ?>
        </div>
        <div class="post-single-comments">
            <div class="post-single-comments-title">
                Комментарии
            </div>

            <form class="post-single-comments-add-form" method="POST" action="">
                @csrf
                <textarea name="comment"></textarea>
                <input type="submit" value="Отправить комментарий">
            </form>

            @foreach ($comments->all() as $comment)
                <div class="post-single-comment-item">
                    {{$comment->content}}<br>
                </div>
            @endforeach
        </div>
    </div>
@endsection
