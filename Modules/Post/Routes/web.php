<?php

use Modules\Post\Http\Controllers\PostController;
use Modules\Post\Http\Controllers\CategoryController;

Route::prefix('post')->group(function() {
    Route::get('/', [PostController::class, 'posts'])->name('posts');
    Route::get('{post_id}', [PostController::class, 'single'])->name('post.single');
    Route::post('{post_id}', [PostController::class, 'single'])->name('post.single');
});

Route::get('category/{category_id}', [CategoryController::class, 'posts'])->name('category.posts');
