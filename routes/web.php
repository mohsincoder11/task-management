<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



Route::get('/', [TaskController::class, 'index']);


Route::resource('tasks', TaskController::class)->except(['show']);
Route::post('tasks/{task}/toggle', [TaskController::class, 'toggleComplete'])->name('tasks.toggle');
Route::post('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
