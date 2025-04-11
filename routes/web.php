<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::get('/', [EmailController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [EmailController::class, 'login'])->name('login');
