<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BannerImageController;
use App\Http\Controllers\API\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
});

Route::group([], function () {
    Route::post('/categories/sort', [CategoryController::class, 'sort']);
    Route::post('/banner-images/sort', [BannerImageController::class, 'sort']);
});

Route::apiResources([
    'categories' => CategoryController::class,
    'banner-images' => BannerImageController::class,
]);
