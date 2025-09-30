<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Temporary: no auth middleware yet. Will add JWT middleware later.
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('{id}', [TaskController::class, 'show']);
    Route::put('{id}', [TaskController::class, 'update']);
    Route::delete('{id}', [TaskController::class, 'destroy']);
});


