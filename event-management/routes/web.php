<?php

use Illuminate\Support\Facades\Route;

/**
 * Web Routes
 */

Route::get('/', static fn () => view('welcome'));
