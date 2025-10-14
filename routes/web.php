<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\UserController;


// Redirect awal ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🟢 LOGIN (mengarah ke AuthController)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'processLogout'])->name('logout');

// 🟢 DASHBOARD (dari AuthController)
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// 🟢 KELOLA PENGGUNA
Route::resource('users', UserController::class);
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/kelola-pengguna', [UserController::class, 'index'])->name('user.management');


// 🟢 DATA SITE
Route::get('/datasite', [\App\Http\Controllers\SiteController::class, 'index'])->name('datasite');
Route::post('/datasite/store', [\App\Http\Controllers\SiteController::class, 'store'])->name('datasite.store');

// Edit Site
Route::get('/datasite/{site}/edit', [\App\Http\Controllers\SiteController::class, 'edit'])->name('datasite.edit');
Route::post('/datasite/{site}/update', [\App\Http\Controllers\SiteController::class, 'update'])->name('datasite.update');
// Hapus Site
Route::post('/datasite/{site}/delete', [\App\Http\Controllers\SiteController::class, 'destroy'])->name('datasite.delete');

// 🟢 UPDATE MAINTENANCE
Route::get('/update-maintenance', [MaintenanceController::class, 'index'])->name('update-maintenance');
// Route::post('/update-maintenance/store', [SiteController::class, 'storeMaintenance'])->name('maintenance.store');