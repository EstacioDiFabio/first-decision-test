<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('user', [UserController::class, 'store'])->name('user.store');
Route::get('users', [UserController::class, 'obtain'])->name('obtain');
