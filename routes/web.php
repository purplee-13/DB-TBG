<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;

// Redirect awal ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// 🟢 LOGIN (mengarah ke AuthController)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::get('/logout', [AuthController::class, 'processLogout'])->name('logout');

// 🟢 DASHBOARD (dari AuthController)
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

// 🟢 DATA SITE
Route::get('/datasite', [Controller::class, 'datasite'])->name('datasite');
Route::post('/datasite/store', [Controller::class, 'storeSite'])->name('datasite.store');

// 🟢 UPDATE MAINTENANCE
Route::get('/update-maintenance', [Controller::class, 'updateMaintenance'])->name('update.maintenance');
Route::post('/update-maintenance/store', [Controller::class, 'storeMaintenance'])->name('maintenance.store');
