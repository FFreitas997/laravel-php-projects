<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

/**
 *  |--------------------------------------------------------------------------
 *  | Web Routes
 *  |--------------------------------------------------------------------------
 */

Route::get('/', fn() => to_route('jobs.index'));

//  Jobs Routes
Route::resource('jobs', JobController::class)->only(['index', 'show']);
