<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\{
    DashboardController,BranchController,ProfileController,ListingController,RolePermissionController
};
use Illuminate\Support\Facades\Route;

// login

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'storelogin']);


Route::middleware(['auth'])->group(function(){

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    Route::resource('/branch', BranchController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //Route::get('/profile', [ProfileController::class,'edit'])->middleware('password.confirm')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::put('/profile', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('/listing', ListingController::class);
    Route::resource('/rolepermission', RolePermissionController::class);
    
});







require __DIR__ . '/auth.php';