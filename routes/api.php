<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\Isc\NotificationController as IscNotificationController;
use App\Http\Controllers\Api\V1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::get('countries', [CountryController::class, 'index']);
        Route::prefix('auth')->group(function () {
            Route::post('introspect', [AuthController::class, 'introspect']);
            Route::post('login', [AuthController::class, 'login']);
            Route::post('register', [AuthController::class, 'register']);
        });
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('notifications', [NotificationController::class, 'index']);
            Route::post('notifications', [NotificationController::class, 'store']);
        });

        Route::prefix('isc')
            ->middleware('isc')
            ->group(function () {
                Route::put('notifications/{notification}/update-status', [IscNotificationController::class, 'updateStatus']);
            });
    });
