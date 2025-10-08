<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman login
Route::get('/login', [AuthController::class, 'index'])->name('login');

// Proses login (form submit)
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout (opsional)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');