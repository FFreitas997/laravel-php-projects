<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


/**
 * Create a new controller for the Book model as well as a new resource route.
 *
 * <p>php artisan make:controller BookController --resource</p>
 */
Route::redirect('/', '/books');
Route::resource('books', BookController::class);
Route::resource('reviews', ReviewController::class);
