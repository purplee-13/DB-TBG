<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // arahkan ke dashboard.blade.php
});

Route::get('/datasite', function () {
    return view('datasite');
});

Route::get('/update-maintenance', function () {
    return view('update-maintenance');
});