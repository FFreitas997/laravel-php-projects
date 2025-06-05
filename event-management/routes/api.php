<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::post("login", [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post("logout", [AuthenticationController::class, 'logout']);

    Route::apiResource('events', EventController::class);

    Route::apiResource('events.attendees', AttendeeController::class)->scoped()->except(['update']);
});


