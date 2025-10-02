<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\UserController;

Route::get('/', [UserController::class, 'create'])->name('create');
Route::post('/user', [UserController::class, 'store'])->name('store');
