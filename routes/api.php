<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiAuthController::class, 'login']);

Route::get('/products', [ProductApiController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductApiController::class, 'store']);
    Route::put('/products/{product}', [ProductApiController::class, 'update']);
    Route::delete('/products/{product}', [ProductApiController::class, 'destroy']);
});
