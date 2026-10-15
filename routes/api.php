<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->middleware(['throttle:login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    // crud user
    Route::post('register/user', [AuthController::class, 'register']);
    Route::put('update/user', [AuthController::class, 'update']);
    Route::delete('delete/user', [AuthController::class, 'delete']);
});