<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TicketController;

Route::get('/tickets', [TicketController::class, 'index']);      // Get All
Route::get('/tickets/{id}', [TicketController::class, 'show']); // Get One
Route::post('/tickets', [TicketController::class, 'store']);     // Create
Route::put('/tickets/{id}', [TicketController::class, 'update']); // Update
Route::delete('/tickets/{id}', [TicketController::class, 'destroy']); // Delete


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
