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

use Modules\Exam\Http\Controllers\ExamController;

Route::prefix('exam')->group(function() {
    Route::get('/', 'ExamController@index')->name('exam');
});
