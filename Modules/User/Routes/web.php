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

use Modules\User\Http\Controllers\UserController;

Route::get('cabinet', [UserController::class, 'cabinet'])->name('cabinet');
Route::get('login', [UserController::class, 'index'])->name('login');
Route::post('custom-login', [UserController::class, 'login'])->name('login.custom');
Route::get('registration', [UserController::class, 'registration'])->name('registration');
Route::post('custom-registration', [UserController::class, 'customRegistration'])->name('registration.custom');
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::get('cabinet/exam_attempt/{attempt_id}', [UserController::class, 'viewExamAttempt'])->name('user.exam_attempt');
