<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/shopping-cart', function () {
    return view('shopping-cart');
});

Route::get('/logout', function () {
    return view('logout');
});
