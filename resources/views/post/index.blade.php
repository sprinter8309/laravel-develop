@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('content')
    <div class="content-block">
        <h2 class='content-title-label'>{{ $title ?? "Статьи" }}</h2>



        @foreach ($posts as $post)
        <div class='posts-list-item'>
            <div class="posts-list-item-category">{{ $post->category }}</div>
            <h3><a href="{{ route('post.single', ['post_id'=>$post->id]) }}">{{ $post->title }}</a></h3>
            <div class="post-preview-image-block">
                <img src="{{ asset($post->image) }}">
            </div>
            <div class='posts-list-preview-block'>
                <?php echo $post->preview; ?>
            </div>
            <br>

        </div>
        @endforeach
    </div>
@endsection