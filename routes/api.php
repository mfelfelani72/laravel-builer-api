<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Registration and Login Routes

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Authenticated Routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
