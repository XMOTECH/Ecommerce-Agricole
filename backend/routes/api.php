<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected user routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Routes publiques pour les catégories et produits (consultation)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Routes protégées pour la gestion (authentification requise)
Route::middleware('auth:sanctum')->group(function () {
    // Gestion des commandes (clients et admins)
    Route::apiResource('orders', OrderController::class);

    // Gestion des catégories (admin seulement)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Gestion des produits (admin seulement)
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Upload d'images pour les produits (admin seulement)
    Route::post('/products/{productId}/upload-image', [ImageController::class, 'uploadProductImage']);
    Route::delete('/products/{productId}/delete-image', [ImageController::class, 'deleteProductImage']);

    // Paiements
    Route::post('/payments/initiate', [PaymentController::class, 'initiatePayment']);
    Route::post('/payments/confirm', [PaymentController::class, 'confirmPayment']);
    Route::get('/payments/{paymentId}/status', [PaymentController::class, 'checkPaymentStatus']);
    Route::post('/payments/simulate', [PaymentController::class, 'simulatePayment']);
});