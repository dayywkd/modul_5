<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// UI Pages
Route::get('/login', [UIController::class, 'login'])->name('login');
Route::get('/register', [UIController::class, 'register'])->name('register');
Route::get('/dashboard', [UIController::class, 'dashboard'])->name('dashboard');
