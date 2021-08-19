<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

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


Route::get('/', [MainController::class, 'index'])->name('index');

Route::get('news', [PostController::class, 'news'])->name('news');

Route::get('posts', [PostController::class, 'posts'])->name('posts');

Route::get('about', [MainController::class, 'about']);

Route::get('post/{post_id}', [PostController::class, 'single'])->name('post.single');

Route::get('category/{category_id}', [CategoryController::class, 'posts'])->name('category.posts');
