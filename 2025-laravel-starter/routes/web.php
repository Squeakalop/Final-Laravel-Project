<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\PublicController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/public', [App\Http\Controllers\PublicController::class, 'index']);
Route::resource('categories', App\Http\Controllers\CategoryController::class);
Route::resource('items', App\Http\Controllers\ItemController::class);
