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
use App\Http\Controllers\Inventory\DataBarangController;
use App\Http\Controllers\Inventory\KategoriBarangController;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Inventory\ExportBarangController;
use App\Http\Controllers\Inventory\PeminjamanController;

// ================= PUBLIC ROUTES =================
Route::get('/me', [AuthController::class, 'me']);
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
        Route::post('/addCustomer', [CustomerController::class, 'add']);
    });
});


// ================= INVENTORY ROUTES =================
Route::prefix('inventory')->middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::get('DataBarang', [DataBarangController::class, 'index']);
    Route::post('TambahDataBarang', [DataBarangController::class, 'store']);
    Route::post('EditDataBarang/{id}', [DataBarangController::class, 'update']);
    Route::post('HapusDataBarang/{id}', [DataBarangController::class, 'destroy']);

    Route::get('Qr-Barang/{id}', [DataBarangController::class, 'generateQr']);
    Route::get('export', [ExportBarangController::class, 'export']);

    Route::get('KategoriBarang', [KategoriBarangController::class, 'index']);
    Route::post('TambahKategoriBarang', [KategoriBarangController::class, 'store']);
    Route::post('HapusKategoriBarang/{id}', [KategoriBarangController::class, 'destroy']);

    Route::post('Peminjaman', [PeminjamanController::class, 'store']);
    Route::post('Pengembalian/{id}', [PeminjamanController::class, 'pengembalian']);
    Route::get('Pinjam', [PeminjamanController::class, 'index']);

});



// ================= SERVICE ROUTES =================
Route::prefix('service')->middleware(['auth:api', 'throttle:60,1'])->group(function (){
    Route::get('getService', [ServiceOrder::class, 'get']);
    Route::get('getVehicles', [VehiclesController::class, 'get']);
    Route::get('getCustomer', [CustomerController::class, 'getData']);

    Route::post('addCustomer', [CustomerController::class, 'add']);
    Route::post('addService', [ServiceOrderController::class, 'add']);
    Route::post('addVehicle', [VehiclesController::class, 'add']);
});
