<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;

// Public ticket endpoints
Route::get('/tickets', [TicketController::class, 'index']);      // Get All (public)
Route::get('/tickets/{id}', [TicketController::class, 'show']); // Get One (public)

// Auth endpoints (register/login are public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require JWT auth via 'auth:api' guard)
Route::middleware('auth:api')->group(function () {
    Route::post('/tickets', [TicketController::class, 'store']);     // Create (auth)
    Route::put('/tickets/{id}', [TicketController::class, 'update']); // Update (auth)
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']); // Delete (auth)

    // Auth ops
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
