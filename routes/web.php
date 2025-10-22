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

// 🟢 LOGIN (mengarah ke AuthController) - TIDAK PERLU AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');

// 🔒 SEMUA ROUTE DI BAWAH INI MEMERLUKAN AUTENTIKASI
Route::middleware(['checkauth'])->group(function () {
    
    // LOGOUT
    Route::post('/logout', [AuthController::class, 'processLogout'])->name('logout');
    
    // DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // KELOLA PENGGUNA
    Route::resource('users', UserController::class);
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/kelola-pengguna', [UserController::class, 'index'])->name('user.management');
    
    // DATA SITE
    Route::get('/datasite', [SiteController::class, 'index'])->name('datasite');
    Route::post('/datasite/store', [SiteController::class, 'store'])->name('datasite.store');
    Route::post('/maintenances/import', [MaintenanceController::class, 'importExcel'])->name('maintenances.import');
    
    // Bulk delete sites
    Route::post('/datasite/delete-multiple', [SiteController::class, 'deleteMultiple'])->name('datasite.deleteMultiple');
    
    // Edit Site
    Route::get('/datasite/{site}/edit', [SiteController::class, 'edit'])->name('datasite.edit');
    Route::post('/datasite/{site}/update', [SiteController::class, 'update'])->name('datasite.update');
    
    // Hapus Site
    Route::post('/datasite/{site}/delete', [SiteController::class, 'destroy'])->name('datasite.delete');
    
    // UPDATE MAINTENANCE
    Route::get('/update-maintenance', [MaintenanceController::class, 'index'])->name('update-maintenance');
    Route::put('/maintenance/{id}', [MaintenanceController::class, 'update'])->name('maintenance.update');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
});