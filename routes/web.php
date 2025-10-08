<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/datasite', function () {
    return view('datasite');
});

Route::get('/update-maintenance', function () {
    return view('update-maintenance');
});