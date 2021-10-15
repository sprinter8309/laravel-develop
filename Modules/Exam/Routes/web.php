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

Route::prefix('exam')->group(function() {
    Route::get('/', 'ExamController@index')->name('exam');
    Route::get('/{exam_url}', 'ExamController@preview')->name('exam.preview');

    Route::get('/start/{exam_id}', 'ExamController@launchExam')->name('exam.begin');
    Route::get('/continue/{exam_id}', 'ExamController@launchExam')->name('exam.continue');
    Route::post('/question/{direction}', 'ExamController@answerOnQuestion')->middleware('exam.check.answer')->name('exam.question');

    Route::post('/finish', 'ExamController@finishExam')->middleware('exam.check.answer')->name('exam.finish');
});
