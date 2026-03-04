<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

//
// Rotas Publicas
//
    // Registro e login
    Route::post('/register', [AuthController::class, 'register']);
    //Route::get('/register', function(){
    //    return "teste";
    //});
    Route::post('/login', [AuthController::class, 'login']);    

    // Sellers
    Route::get('/sellers', [SellerController::class, 'index']);
    Route::get('/sellers/{id}', [SellerController::class, 'show']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'publicShow']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

//
// Rotas Autenticadas
//

    // Registro e logout
    Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    });

    // Sellers
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/seller/profile', [SellerController::class, 'profile']);
    Route::post('/seller', [SellerController::class, 'store']);
    Route::put('/seller', [SellerController::class, 'update']);
    Route::delete('/seller', [SellerController::class, 'destroy']);
    Route::get('/seller/profile', [SellerController::class, 'getSeller']);
    Route::put('/seller/update', [SellerController::class, 'updateSeller']);
    });

    // Produtos do Seller autenticado
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/seller/products', [ProductController::class, 'myProducts']);
    });

    // Categories
    Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});

    // Customers
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customer/profile', [CustomerController::class, 'getCustomer']);
    Route::put('/customer/update', [CustomerController::class, 'updateCustomer']);
    });

//
// Rotas acessíveis apenas pelo Admin
//

    Route::middleware('auth:sanctum')->group(function () {

    // Admin customers
    Route::get('/admin/customers', [AdminController::class, 'listarcustomers']);
    Route::get('/admin/customers/{id_customer}', [AdminController::class, 'showCustomer']);
    Route::put('/admin/customers/{id_customer}', [AdminController::class, 'atualizarCustomer']);
    
    // Admin seller
    Route::get('/admin/sellers', [AdminController::class, 'listarseller']);
    Route::put('/admin/sellers/{id_seller}', [AdminController::class, 'atualizarSeller']);
    Route::delete('/admin/sellers/{id_seller}',   [AdminController::class, 'excluirseller']);

});;


    // Rotas de produtos (Seller ou Admin)
    Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['auth:sanctum', 'role:seller,admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/seller/products/{id}', [ProductController::class, 'show']);
    Route::delete('/seller/products/{id}', [ProductController::class, 'destroy']);
    });

    Route::middleware(['auth:sanctum', 'role:seller,admin'])->group(function () {
    Route::put('/seller/products/{id}', [ProductController::class, 'update']);
    });
    
    Route::middleware(['auth:sanctum', 'role:admin'])
    ->get('/admin/products', [ProductController::class, 'adminIndex']);
});