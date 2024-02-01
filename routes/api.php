

<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReBuyDetailController;
use App\Http\Controllers\SalePointController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::name('api.')->group(function () {
    Route::get("/test", function () {
        return "test";
    });
    Route::post('/tenant', [TenantController::class, 'create']);

    Route::middleware(['tenant'])->group(function () {
        Route::post('/stores', [StoreController::class, 'create']);
        Route::get('/stores', [StoreController::class, 'index']);
        Route::get('/stores/{id}', [StoreController::class, 'oneStore']);
        Route::delete('/stores/{id}', [StoreController::class, 'Delete']);


        Route::post('/sale-point', [SalePointController::class, 'create']);
        Route::get('/sale-point', [SalePointController::class, 'index']);
        Route::get('/sale-point/{id}', [SalePointController::class, 'oneStore']);
        Route::delete('/sale-point/{id}', [SalePointController::class, 'Delete']);

        // Category api

        Route::post('/categories', [CategoryController::class, 'create']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'oneStore']);
        Route::delete('/categories/{id}', [CategoryController::class, 'Delete']);


        // Unit api

        Route::post('/units', [UnitController::class, 'create']);
        Route::get('/units', [UnitController::class, 'index']);
        Route::get('/units/{id}', [UnitController::class, 'oneStore']);
        Route::delete('/units/{id}', [UnitController::class, 'Delete'],);

        // Product api

        Route::post('/products', [ProductController::class, 'create']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'oneStore']);
        Route::delete('/products/{id}', [ProductController::class, 'Delete']);


        // Supplier api

        Route::post('/suppliers', [SupplierController::class, 'create']);
        Route::get('/suppliers', [SupplierController::class, 'index']);
        Route::get('/suppliers/{id}', [SupplierController::class, 'oneStore']);
        Route::delete('/suppliers/{id}', [SupplierController::class, 'Delete']);



        // bill api

        Route::post('/bills', [BillController::class, 'create']);
        Route::get('/bills', [BillController::class, 'index']);
        Route::get('/bills/{id}', [BillController::class, 'oneStore']);
        Route::delete('/bills/{id}', [BillController::class, 'Delete']);


        Route::post('/re-buy-bills', [ReBuyDetailController::class, 'ReBuy']);
    });
});
