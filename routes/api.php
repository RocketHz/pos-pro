<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/all', [ProductController::class, 'all']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Orders
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// Tables
Route::get('/tables', [TableController::class, 'index']);
Route::patch('/tables/{id}', [TableController::class, 'updateStatus']);
Route::post('/tables/{id}/free', [TableController::class, 'free']);

// Coupons
Route::post('/coupons/check', [CouponController::class, 'check']);
Route::get('/coupons', [CouponController::class, 'index']);
Route::post('/coupons', [CouponController::class, 'store']);
Route::put('/coupons/{id}', [CouponController::class, 'update']);
Route::delete('/coupons/{id}', [CouponController::class, 'destroy']);

// Dashboard
Route::get('/dashboard-stats', [DashboardController::class, 'index']);
Route::get('/reports/weekly-sales', [DashboardController::class, 'weeklySales']);
Route::get('/reports/payment-methods', [DashboardController::class, 'paymentMethods']);
Route::get('/reports/recent-transactions', [DashboardController::class, 'recentTransactions']);
