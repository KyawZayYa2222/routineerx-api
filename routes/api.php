<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\RoutineTaskController;
use Illuminate\Support\Facades\Route;

// api version prefix group
Route::prefix('v1')->group(function () {
    // Routes for registration and login
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Routine task routes
        // Route::prefix('/routine-tasks')->controller(RoutineTaskController::class)->group(function () {
        //     Route::get('/', 'index');
        //     Route::get('/{id}', 'show');
        //     Route::post('/', 'create');
        //     Route::put('/{id}', 'update');
        //     Route::delete('/{id}', 'destroy');
        // });
        Route::apiResource('/routine-tasks', RoutineTaskController::class);
    });
});
