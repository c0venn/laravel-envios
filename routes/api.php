<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


Route::post('token', [ApiController::class, 'token']);
Route::get('regions', [ApiController::class, 'getRegions']);
Route::post('getRate', [ApiController::class, 'getRate']);
Route::middleware('jwt')->group(function () {
    Route::post('order', [ApiController::class, 'submitOrder']);
});