<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


//Route publique phase 2
Route::get('categories', [CategoryController::class, 'index']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Routes pour le  cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'add']);
    Route::put('cart/update/{productId}', [CartController::class, 'update']);
    Route::delete('cart/remove/{productId}', [CartController::class, 'remove']);
    Route::post('cart/sync', [CartController::class, 'sync']);

    // Route de l addresse
    //Route::resource('addresses', AddressController::class)->only(['index', 'store']);
    Route::get('addresses', [AddressController::class, 'index']);
    Route::post('addresses', [AddressController::class, 'store']);


    //route de la cheickout
    Route::post('checkout', [CheckoutController::class, 'store']);

    // Route dela commande
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
});

Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin|super-admin']) //  Ajout de auth:sanctum
    ->group(function () {
        // Gestion des Produits
        Route::get('products', [\App\Http\Controllers\Api\Admin\ProductController::class, 'index']);
        Route::post('products', [\App\Http\Controllers\Api\Admin\ProductController::class, 'store']);
        Route::get('products/{product}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'show']);
        Route::put('products/{product}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'update']);
        Route::delete('products/{product}', [\App\Http\Controllers\Api\Admin\ProductController::class, 'destroy']);

        // Gestion des Commandes
        Route::get('orders', [\App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
        Route::get('orders/{order}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
        Route::put('orders/{order}/status', [\App\Http\Controllers\Api\Admin\OrderController::class, 'updateStatus']);
    });
