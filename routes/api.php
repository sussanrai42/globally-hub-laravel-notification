<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('v1')
    ->group(function () {
        Route::get('/', function () {
            return response([
                'status' => true
            ]);
        });
    });
