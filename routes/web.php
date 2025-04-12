<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::get('/', [EmailController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [EmailController::class, 'login'])->name('login');
Route::get('/dashboard', [EmailController::class, 'index'])->name('dashboard');
Route::get('/change-email', [EmailController::class, 'changeEmail'])->name('change.email');
Route::get('/campaigns', [EmailController::class, 'campaigns'])->name('campaigns');
Route::get('/all-data', [EmailController::class, 'allData'])->name('all.data');
Route::get('/logout', [EmailController::class, 'logout'])->name('logout');
