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

            <form class="post-single-comments-add-form" method="POST" action="{{ route('post.single', ['post_id'=>$post->id]) }}">
                @csrf
                <textarea name="comment"></textarea>

                @if ($errors->has('comment'))
                    <div class="result-form-message error-message">{{$errors->first('comment')}}</div>
                @endif

                @if (!$errors->any() && !empty($error))
                    <div class="result-form-message error-message">{{$error}}</div>
                @endif

                @if (!$errors->any() && !empty($message))
                    <div class="result-form-message success-message">{{$message}}</div>
                @endif

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
