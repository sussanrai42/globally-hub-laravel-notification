<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('test/command', [TestController::class, 'create']);

Route::prefix('v1')
    ->group(function () {
        Route::get('countries', [CountryController::class, 'index']);
        Route::post('user/login', [AuthController::class, 'login']);
        Route::post('user/register', [AuthController::class, 'register']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('notifications', [NotificationController::class, 'index']);
            Route::post('notifications', [NotificationController::class, 'store'])->middleware('throttle:5,1');
        });
    });
