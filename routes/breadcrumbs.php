<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use App\Models\Post;
use App\Models\News;
use App\Models\Category;

Breadcrumbs::for('index', function (BreadcrumbTrail $trail) {
    $trail->push('Главная', route('index'));
});

Breadcrumbs::for('about', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('О блоге', route('about'));
});



Breadcrumbs::for('posts', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Статьи', route('posts'));
});

Breadcrumbs::for('post.single', function (BreadcrumbTrail $trail, $post_id) {
    $trail->parent('index');
    $trail->push('Статьи', route('posts'));
    $trail->push(Post::findOrFail($post_id)->title ?? '', route('post.single', ['post_id'=>$post_id]));
});

Breadcrumbs::for('category.posts', function (BreadcrumbTrail $trail, $category_id) {
    $trail->parent('index');
    $trail->push(Category::findOrFail($category_id)->name ?? '', route('category.posts', ['category_id'=>$category_id]));
});



Breadcrumbs::for('news', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Новости', route('news'));
});

Breadcrumbs::for('news.single', function (BreadcrumbTrail $trail, $news_id) {
    $trail->parent('index');
    $trail->push('Новости', route('news'));
    $trail->push(News::findOrFail($news_id)->title ?? '', route('news.single', ['news_id'=>$news_id]));
});



Breadcrumbs::for('exam', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Тесты', route('exam'));
});



Breadcrumbs::for('cabinet', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Личный кабинет', route('cabinet'));
});

Breadcrumbs::for('login', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Вход в систему', route('login'));
});

Breadcrumbs::for('registration', function (BreadcrumbTrail $trail) {
    $trail->parent('index');
    $trail->push('Регистрация', route('registration'));
});
