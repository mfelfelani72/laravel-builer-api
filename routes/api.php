<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Registration and Login Routes

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Authenticated Routes
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
