<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/books');
Route::resource('books', BookController::class);
Route::resource('reviews', ReviewController::class);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
