<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventory\ItemCategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\ItemTypeController;
use App\Http\Controllers\Inventory\StockMovementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);

//admin routes
Route::prefix('admin')->group(function () {
    Route::get('/getUserData', [AuthController::class, 'getUserData']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/updateUser/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

//inventory routes
Route::prefix('inventory')->group(function () {
    Route::apiResource('item-types', ItemTypeController::class);
    Route::apiResource('item-categories', ItemCategoryController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('stock-movements', StockMovementController::class);
});
