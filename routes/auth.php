<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    // Register
    Route::get('/register',[RegisterController::class,'create'])->name('register');
    Route::post('/register',[RegisterController::class,'store']);
    // login
    Route::get('/login', [RegisterController::class, 'login'])->name('login');
    Route::post('/login', [RegisterController::class, 'storelogin']);
    //forget password
    Route::get('/forgot-password',[RegisterController::class, 'requestPass'])->name('password.request');
    Route::post('/forgot-password',[RegisterController::class, 'sendEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [RegisterController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [RegisterController::class, 'resetHandler'])->name('password.update');
});

Route::middleware('auth')->group(function(){
    // logout
    Route::post('/logout',[RegisterController::class,'destory'])->name('logout');
    //email verification
    Route::get('/email/verify',[RegisterController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'handler'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', [RegisterController::class, 'resend'])->middleware( 'throttle:6,1'  )->name('verification.send');
    
    //Password Confirmation
    Route::get('/confirm-password', [ConfirmPasswordController::class, 'create'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'store'])->middleware('throttle:6,1');
});