<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('api')->group(function(){
    // Registro e login
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/register', function(){
        return "teste";
    });
    Route::post('/login', [AuthController::class, 'login']);    
});