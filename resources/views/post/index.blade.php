@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>{{ $title ?? "Статьи" }}</h2>
@endsection

@section('content')
    <div class="content-block">
        <div class="posts-list-container">
            @foreach ($posts as $post)
                <div class='posts-list-item'>
                    <a href="{{ route('post.single', ['post_id'=>$post->id]) }}" class="posts-list-item-container">
                        <div class="posts-list-item-category">{{ $post->category }}</div>
                        <h3>{{ $post->title }}</h3>
                        <div class="post-preview-image-block">
                            <img src="{{ asset($post->image) }}">
                        </div>
                        <div class='posts-list-preview-block'>
                            <?php echo $post->preview; ?>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
