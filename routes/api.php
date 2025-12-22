<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;

// Public ticket endpoints
Route::get('/tickets', [TicketController::class, 'index']);      // Get All (public)
Route::get('/tickets/{slug}', [TicketController::class, 'show']); // Get One (public, by slug)

// Categories (public)
use App\Http\Controllers\CategoryController;
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Auth endpoints (register/login are public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require JWT auth via 'auth:api' guard)
Route::middleware('auth:api')->group(function () {
    Route::post('/tickets', [TicketController::class, 'store']);     // Create (auth)
    Route::put('/tickets/{slug}', [TicketController::class, 'update']); // Update (auth, by slug)
    Route::delete('/tickets/{slug}', [TicketController::class, 'destroy']); // Delete (auth, by slug)

    // Category protected ops
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{slug}', [CategoryController::class, 'update']);
    Route::delete('/categories/{slug}', [CategoryController::class, 'destroy']);

    // Auth ops
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
