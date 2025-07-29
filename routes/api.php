<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::group(['prefix' => 'v1'], function() {

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('test', TestController::class);
    });

});

Route::post('v1/register', [RegisterController::class, 'store']);
Route::post('v1/login', [LoginController::class, 'login']);
