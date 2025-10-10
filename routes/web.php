<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\MaintenanceController;

// Redirect awal ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🟢 LOGIN (mengarah ke AuthController)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::get('/logout', [AuthController::class, 'processLogout'])->name('logout');

// 🟢 DASHBOARD (dari AuthController)
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// 🟢 DATA SITE
Route::get('/datasite', [SiteController::class, 'datasite'])->name('datasite');
Route::post('/datasite/store', [SiteController::class, 'storeSite'])->name('datasite.store');

// 🟢 UPDATE MAINTENANCE
Route::get('/update-maintenance', [MaintenanceController::class, 'index'])->name('update-maintenance');
// Route::post('/update-maintenance/store', [SiteController::class, 'storeMaintenance'])->name('maintenance.store');
