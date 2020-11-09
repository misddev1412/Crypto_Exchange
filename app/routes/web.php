<?php

use App\Http\Controllers\Exchange\CoinMarketController;
use App\Http\Controllers\GoogleTwoFactor\VerifyGoogle2faController;
use App\Http\Controllers\Guest\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Page\ViewPageController;
use App\Http\Controllers\Post\BlogCategoryController;
use App\Http\Controllers\Post\BlogController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
//Test
Route::get('test', [TestController::class, 'test'])
    ->name('test');
Route::get('/', HomeController::class)
    ->name('home');
Route::post('google-2fa/verify', [VerifyGoogle2faController::class, 'verify'])
    ->name('profile.google-2fa.verify');

//Blog
Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('blog/category/{postCategory}', [BlogCategoryController::class, 'index'])->name('blog.category');
Route::get('blog/post/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/{page?}', [ViewPageController::class, 'index'])->name('page.index');
