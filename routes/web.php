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
Route::get('/datasite', [\App\Http\Controllers\SiteController::class, 'index'])->name('datasite');
Route::post('/datasite/store', [\App\Http\Controllers\SiteController::class, 'store'])->name('datasite.store');

// Edit Site
Route::get('/datasite/{site}/edit', [\App\Http\Controllers\SiteController::class, 'edit'])->name('datasite.edit');
Route::post('/datasite/{site}/update', [\App\Http\Controllers\SiteController::class, 'update'])->name('datasite.update');
// Hapus Site
Route::post('/datasite/{site}/delete', [\App\Http\Controllers\SiteController::class, 'destroy'])->name('datasite.delete');

// 🟢 UPDATE MAINTENANCE
Route::get('/update-maintenance', [Controller::class, 'updateMaintenance'])->name('update.maintenance');
Route::post('/update-maintenance/store', [Controller::class, 'storeMaintenance'])->name('maintenance.store');
