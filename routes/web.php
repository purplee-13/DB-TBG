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

// Bulk delete sites
Route::post('/datasite/delete-multiple', [\App\Http\Controllers\SiteController::class, 'deleteMultiple'])->name('datasite.deleteMultiple');

// Edit Site
Route::get('/datasite/{site}/edit', [\App\Http\Controllers\SiteController::class, 'edit'])->name('datasite.edit');
Route::post('/datasite/{site}/update', [\App\Http\Controllers\SiteController::class, 'update'])->name('datasite.update');
// Hapus Site
Route::post('/datasite/{site}/delete', [\App\Http\Controllers\SiteController::class, 'destroy'])->name('datasite.delete');

// 🟢 UPDATE MAINTENANCE
Route::get('/update-maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
Route::put('/maintenance/{id}', [MaintenanceController::class, 'update'])->name('maintenance.update');

// Temporary dev helpers (only in local environment)
// if (app()->environment('local')) {
//     Route::get('/dev-login/{username}', function ($username) {
//         $user = \App\Models\User::where('username', $username)->first();
//         if (! $user) {
//             abort(404, 'User not found');
//         }

//         session([
//             'user_id' => $user->id,
//             'username' => $user->username,
//             'name' => $user->name,
//             'role' => $user->role,
//         ]);

//         return redirect()->route('dashboard');
//     })->name('dev.login');

//     Route::get('/dashboard-test', function () {
//         if (! session()->has('user_id')) {
//             return response()->json(['logged_in' => false, 'session' => session()->all()]);
//         }
//         return response()->json(['logged_in' => true, 'session' => session()->only(['user_id','username','name','role'])]);
//     });

//     // Dev: quick update user for testing (local only)
//     Route::get('/dev-update-user/{id}', function ($id, \Illuminate\Http\Request $request) {
//         $user = \App\Models\User::find($id);
//         if (! $user) return response()->json(['ok' => false, 'message' => 'User not found'], 404);

//         $name = $request->query('name');
//         $username = $request->query('username');
//         $role = $request->query('role');
//         $password = $request->query('password');

//         if ($name) $user->name = $name;
//         if ($username) $user->username = $username;
//         if ($role) $user->role = strtolower($role);
//         if ($password) $user->password = bcrypt($password);

//         $user->save();
//         return response()->json(['ok' => true, 'user' => $user]);
//     });
// }
