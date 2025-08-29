<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventory\ItemCategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\ItemTypeController;
use App\Http\Controllers\Inventory\StockMovementController;


// ================= PUBLIC ROUTES =================
Route::get('/me', [AuthController::class, 'me']);
Route::post('/refresh', [AuthController::class, 'refresh']);

// ================= ADMIN ROUTES =================
Route::prefix('admin')->group(function () {
    // Public (no middleware)
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Protected (JWT)
    Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/getUserData', [AuthController::class, 'getUserData']);
        Route::post('/updateProfile', [AuthController::class, 'update']);
    });
});

// ================= INVENTORY ROUTES =================
Route::prefix('inventory')->middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::apiResource('item-types', ItemTypeController::class);
    Route::apiResource('item-categories', ItemCategoryController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('stock-movements', StockMovementController::class);
});
