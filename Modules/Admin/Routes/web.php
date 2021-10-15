<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Admin\Http\Controllers\AdminController;

Route::prefix('admin')->middleware('admin.entrance')->group(function() {
    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('/posts', 'AdminController@posts')->name('admin.posts');

    Route::get('/posts/create', [AdminController::class, 'createPost'])->name('admin.posts.create');
    Route::post('/posts/create', [AdminController::class, 'storePost'])->name('admin.posts.create');

    Route::get('/posts/update/{update_post_id}', [AdminController::class, 'updatePost'])->name('admin.posts.update');
    Route::post('/posts/try-update', [AdminController::class, 'editPost'])->name('admin.posts.try-update');

    Route::get('/posts/delete/{delete_post_id}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');
});
