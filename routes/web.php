<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
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

Route::get('/', [PostController::class, 'posts'])->name('index');

Route::get('news', [NewsController::class, 'news'])->name('news');

Route::get('news/{news_id}', [NewsController::class, 'single'])->name('news.single');

Route::get('posts', [PostController::class, 'posts'])->name('posts');

Route::get('about', [MainController::class, 'about']);

Route::get('post/{post_id}', [PostController::class, 'single'])->name('post.single');

Route::get('category/{category_id}', [CategoryController::class, 'posts'])->name('category.posts');

Route::get('about', [MainController::class, 'about']);

Route::get('cabinet', [AuthController::class, 'cabinet']);
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('custom-login', [AuthController::class, 'login'])->name('login.custom');
Route::get('registration', [AuthController::class, 'registration'])->name('registration');
Route::post('custom-registration', [AuthController::class, 'customRegistration'])->name('registration.custom');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
