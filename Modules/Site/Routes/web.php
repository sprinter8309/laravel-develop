<?php

use Modules\Site\Http\Controllers\SiteController;

Route::get('/', [SiteController::class, 'index'])->name('index');

Route::get('about', [SiteController::class, 'about'])->name('about');
