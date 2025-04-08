<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Redirect the root URL to the book index page
Route::redirect('/', '/books');

// Define resource routes for books
Route::resource('books', BookController::class)
    ->except(['edit', 'update']);

// Define resource routes for reviews
Route::resource('books.reviews', ReviewController::class)
    ->scoped(['review' => 'book'])
    ->only(['create', 'store']);
