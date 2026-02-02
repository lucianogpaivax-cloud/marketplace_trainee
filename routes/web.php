<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//Route::redirect('/', '/login');

// login
//Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// // customer
// Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth');

// // seller
// Route::get('/dashboard-seller', [AuthController::class, 'dashboardseller'])->middleware('auth');

// // admin
// Route::get('/dashboard-admin', [AuthController::class, 'dashboardAdmin'])->middleware('auth');

// // form cadastro
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
// Route::post('/register', [AuthController::class, 'register'])->name('register');