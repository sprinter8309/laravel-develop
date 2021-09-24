<?php

use Modules\News\Http\Controllers\NewsController;

Route::prefix('news')->group(function() {
    Route::get('/', [NewsController::class, 'news'])->name('news');
    Route::get('{news_id}', [NewsController::class, 'single'])->name('news.single');
});
