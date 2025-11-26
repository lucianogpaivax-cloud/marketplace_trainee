<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


// Registro e login
    Route::post('/register', [AuthController::class, 'register']);
    //Route::get('/register', function(){
    //    return "teste";
    //});
    Route::post('/login', [AuthController::class, 'login']);    



// Rotas autenticadas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
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

// Rotas acessíveis apenas pelo Admin
    Route::middleware('auth:sanctum')->group(function () {

    // Clientes
    Route::get('/admin/customers', [AdminController::class, 'listarClientes']);
    Route::get('/admin/customers/{id_customer}', [AdminController::class, 'showCustomer']);
    Route::put('/admin/customers/{id_customer}', [AdminController::class, 'atualizarCustomer']);
    

    // Vendedores
    Route::get('/admin/sellers', [AdminController::class, 'listarVendedores']);
    Route::put('/admin/sellers/{id_seller}', [AdminController::class, 'atualizarVendedor']);
    Route::delete('/admin/sellers/{id_seller}',   [AdminController::class, 'excluirVendedor']);

});;

// Rotas acessíveis pelo cliente
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/costumer/profile', [CustomerController::class, 'profile']);
    Route::put('/costumer/profile', [CustomerController::class, 'update']);
});