<?php

use App\Http\Controllers\Core\VerificationController;
use Illuminate\Support\Facades\Route;


Route::get('verification', [VerificationController::class, 'verify'])->name('account.verification');
Route::get('verification/email', [VerificationController::class, 'resendForm'])->name('verification.form');
Route::post('verification/email', [VerificationController::class, 'send'])->name('verification.send');
