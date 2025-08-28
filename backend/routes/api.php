<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventory\ItemCategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\ItemTypeController;
use App\Http\Controllers\Inventory\StockMovementController;

// ================= ADMIN ROUTES =================
Route::prefix('admin')->group(function () {
    // Public (no middleware)
    Route::post('/login', [AuthController::class, 'login']);

    // Protected (JWT)
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/getUserData', [AuthController::class, 'getUserData']);
    });
});

// ================= INVENTORY ROUTES =================
Route::prefix('inventory')->middleware('auth:api')->group(function () {
    Route::apiResource('item-types', ItemTypeController::class);
    Route::apiResource('item-categories', ItemCategoryController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('stock-movements', StockMovementController::class);
});
