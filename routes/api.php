<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Registro e login
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// Rotas autenticadas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/me', [ApiAuthController::class, 'me']);
});

// SELLERS
// Rotas protegidas (usuário autenticado)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/seller/profile', [SellerController::class, 'profile']);
    Route::post('/seller', [SellerController::class, 'store']);
    Route::put('/seller', [SellerController::class, 'update']);
    Route::delete('/seller', [SellerController::class, 'destroy']);
});

// Rotas públicas
Route::get('/sellers', [SellerController::class, 'index']);
Route::get('/sellers/{id}', [SellerController::class, 'show']);


// PRODUCTS
// Rotas públicas
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Rotas protegidas (vendedor autenticado)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/seller/products', [ProductController::class, 'myProducts']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// CONTROLLER
// Rotas públicas (listagem e visualização)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Rotas protegidas (criação, atualização e exclusão)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});