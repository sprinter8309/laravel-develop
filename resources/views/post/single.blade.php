@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'><?php echo $post->title; ?></h2>
@endsection

@section('content')
    <div class="content-block">
        <div class="post-single-content">
            <?php echo $post->content; ?>
        </div>
        <div class="post-single-info">
            <div class="post-single-author">Автор: <?php echo $author->name . ' ' . $author->lastname; ?></div>
            Добавлено: <?php echo date_create($post->created_at)->format("d.m.Y"); ?>
        </div>
        <div class="post-single-other-section-articles">
            <h2>Другие статьи раздела "{{ $post->section->name }}":</h2>
            <div>
                @foreach ($random_section_posts as $random_post_item)
                    <a href="{{ route('post.single', ['post_id'=>$random_post_item->id]) }}" class="post-single-other-section-link">
                        <div class="post-single-other-section-item">
                            <img src="{{ asset($random_post_item->image) }}">
                            <h3>{{ $random_post_item->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
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

                    <div class="post-single-comment-item-author">{{$comment->user->name}}</div>
                    <div class="post-single-comment-item-date">{{date_create($comment->created_at)->format("d M Y   H:i")}}</div>

                    <div class="post-single-comment-item-text">
                        {{$comment->content}}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
