<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MidtransController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/login', [AuthController::class, 'login']);
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])->name('midtrans.callback');
