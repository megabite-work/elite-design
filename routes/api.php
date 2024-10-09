<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BannerImageController;
use App\Http\Controllers\API\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::apiResources([
    'categories' => CategoryController::class,
    'banner-images' => BannerImageController::class,
], ['middleware' => 'auth:api']);
