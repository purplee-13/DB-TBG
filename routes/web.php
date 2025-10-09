<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

// Redirect awal ke login
Route::get('/', function () {
    return redirect('/login');
});

// 🟢 LOGIN
Route::get('/login', [Controller::class, 'login'])->name('login');
Route::post('/login', [Controller::class, 'loginPost'])->name('login.post');
Route::get('/logout', [Controller::class, 'logout'])->name('logout');

// 🟢 DASHBOARD
Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');

// 🟢 DATA SITE
Route::get('/datasite', [Controller::class, 'datasite'])->name('datasite');
Route::post('/datasite/store', [Controller::class, 'storeSite'])->name('datasite.store');

// 🟢 UPDATE MAINTENANCE
Route::get('/update-maintenance', [Controller::class, 'updateMaintenance'])->name('update.maintenance');
Route::post('/update-maintenance/store', [Controller::class, 'storeMaintenance'])->name('maintenance.store');
