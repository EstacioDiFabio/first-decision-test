<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('users', [UserController::class, 'index'])->name('users.list');
Route::post('user', [UserController::class, 'store'])->name('user.store');
