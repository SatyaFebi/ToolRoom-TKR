<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Inventory\ItemCategoryController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Inventory\ItemTypeController;
use App\Http\Controllers\Inventory\StockMovementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Service\ServiceOrderController;
use App\Http\Controllers\Service\VehiclesController;
use App\Http\Controllers\Service\CustomerController;
use App\Models\Service\ServiceOrder;
use Illuminate\Support\Facades\Request;

// ================= PUBLIC ROUTES =================
// Route::get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json($request->user());
});
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/getRole', [RoleController::class, 'index']);


// ================= ADMIN ROUTES =================
Route::prefix('admin')->group(function () {
    // Public (no middleware)
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/editUser/{id}', [AuthController::class, 'editUser']);
    Route::post('/deleteUser/{id}', [AuthController::class, 'deleteUser']);

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


// ================= SERVICE ROUTES =================
Route::prefix('service')->middleware(['auth:api', 'throttle:60,1'])->group(function (){
    Route::get('getService', [ServiceOrder::class, 'get']);

    Route::post('addCustomer', [CustomerController::class, 'add']);
    Route::post('addService', [ServiceOrderController::class, 'add']);
    Route::post('addVehicle', [VehiclesController::class, 'add']);
});
