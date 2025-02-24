<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TaskController::class, 'index'])->name('home');

Route::prefix('/tasks')->group(function () {

    Route::get('/', [TaskController::class, 'showAllCompletedTasks'])->name('tasks.index');
    Route::get('/create', [TaskController::class, 'showCreate'])->name('tasks.create');
    Route::post('/create', [TaskController::class, 'createTask'])->name('tasks.store');
    Route::get('/{id}/edit', [TaskController::class, 'showEdit'])->name('tasks.edit');
    Route::put('/{id}/edit', [TaskController::class, 'updateTask'])->name('tasks.update');
    Route::get('/{id}', [TaskController::class, 'showTask'])->name('tasks.show');
    Route::delete('/{id}', [TaskController::class, 'deleteTask'])->name('tasks.delete');
});

Route::fallback([TaskController::class, 'fallback']);
