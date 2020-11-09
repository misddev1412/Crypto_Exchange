<?php

use App\Http\Controllers\Core\ProductActiveController;
use App\Http\Controllers\Guest\AuthController;
use App\Http\Controllers\Post\BlogController;
use Illuminate\Support\Facades\Route;

//Login
Route::get('login', [AuthController::class, 'loginForm'])
    ->name('login');
Route::post('login', [AuthController::class, 'login'])
    ->name('login.post');

//Registration
Route::get('register', [AuthController::class, 'register'])
    ->name('register.index')->middleware('registration.permission');
Route::post('register/store', [AuthController::class, 'storeUser'])
    ->name('register.store')->middleware('registration.permission');

//Reset Password
Route::get('forget-password', [AuthController::class, 'forgetPassword'])
    ->name('forget-password.index');
Route::post('forget-password/send-mail', [AuthController::class, 'sendPasswordResetMail'])
    ->name('forget-password.send-mail');
Route::post('product-activation', [ProductActiveController::class, 'store'])
    ->name('product-activation');
Route::get('reset-password/{user}', [AuthController::class, 'resetPassword'])
    ->name('reset-password.index');
Route::post('reset-password/{user}/update', [AuthController::class, 'updatePassword'])
    ->name('reset-password.update');

