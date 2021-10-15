@extends('layouts.app')

@section('header')
    @include('widgets._high_menu')
@endsection

<?php
use App\Models\Post;
$action_route = ($action_type===Post::POST_CREATE) ? 'admin.posts.create' : 'admin.posts.try-update';
?>

@section('title-content-label')
    <h2 class='content-title-label'>
        @if ($action_type===Post::POST_UPDATE)
            Редактирование статьи "{{ $update_data->title }}"
        @else
            Создание новой статьи
        @endif
    </h2>
@endsection

@section('content')

    @if ($errors->any())
        <div class="admin-posts-edit-message-block">
            @if ($action_type===Post::POST_UPDATE)
                Полученные данные содержали ошибки, исправьте их чтобы отредактировать статью.
            @else
                Полученные данные содержали ошибки, исправьте их чтобы создать статью.
            @endif
        </div>
    @endif

    <div class="admin-posts-edit-container">

        <form class="admin-posts-edit-form" method="POST" action="{{ route($action_route) }}"  enctype="multipart/form-data">
            @csrf

            <div class="admin-posts-edit-field-container">
                <h3>Название статьи:</h3>
                <input name="title" type="text" value="{{ ($action_type===Post::POST_UPDATE) ? $update_data->title:'' }}">
                @error('title')
                    <div class="admin-posts-edit-field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-posts-edit-field-container">
                <h3>Текст превью:</h3>
                <textarea name="preview" class="admin-posts-edit-preview-textarea">{{ ($action_type===Post::POST_UPDATE) ? $update_data->preview:'' }}</textarea>
                @error('preview')
                    <div class="admin-posts-edit-field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-posts-edit-field-container">
                <h3>Текст статьи:</h3>
                <textarea name="content" class="admin-posts-edit-detail-textarea">{{ ($action_type===Post::POST_UPDATE) ? $update_data->content:'' }}</textarea>
                @error('content')
                    <div class="admin-posts-edit-field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-posts-edit-field-container">
                <h3>Загрузить изображение превью (предпочтительно 380x260):</h3>
                <input name="image" type="file" accept="image/*">
                @error('image')
                    <div class="admin-posts-edit-field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-posts-edit-field-container">
                <h3>Категория статьи:</h3>
                <select name="category">
                    @if ($action_type===Post::POST_UPDATE)
                        @foreach ($categories as $category)
                            <option value="{{ $category["id"] }}" <?php if ($category["id"]===$update_data->category_id) echo "selected"; ?> >{{ $category["name"] }}</option>
                        @endforeach
                    @else
                        @foreach ($categories as $category)
                            <option value="{{ $category["id"] }}">{{ $category["name"] }}</option>
                        @endforeach
                    @endif
                </select>
                @error('category')
                    <div class="admin-posts-edit-field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-posts-edit-control-buttons">
                @if ($action_type===Post::POST_UPDATE)
                    <input type="submit" value="Отредактировать статью" class="admin-posts-edit-submit">
                @else
                    <input type="submit" value="Создать новую статью" class="admin-posts-edit-submit">
                @endif

                <a href="{{ route("admin.posts") }}" class="admin-posts-edit-return">Вернуться назад</a>
            </div>

            @if ($action_type===Post::POST_UPDATE)
                <input type="hidden" name="id" value="{{ $id }}">
            @endif
        </form>
    </div>
@endsection
