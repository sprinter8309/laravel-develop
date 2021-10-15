@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

@section('title-content-label')
    <h2 class='content-title-label'>Статьи портала</h2>
@endsection

<script src="{{ asset("js/simple_grid_view.js") }}" defer></script>

@section('content')

    @if (isset($success_message))
        <div class="admin-posts-index-message admin-posts-index-success">
            {{ $success_message }}
        </div>
    @endif

    @if (isset($error))
        <div class="admin-posts-index-message admin-posts-index-error">
            {{ $error }}
        </div>
    @endif

    <div class="admin-posts-index-container">
        @include('admin.posts_index_grid', [
            'data_provider'=>$post_index_data_provider,
        ])
        <div class="admin-posts-index-buttons">
            <a href="{{ route("admin.posts.create") }}" class="admin-posts-index-create-button">Создать статью</a>
            <a href="{{ route("admin") }}" class="admin-posts-index-return-button">Вернуться на главную</a>
        </div>
    </div>
@endsection
