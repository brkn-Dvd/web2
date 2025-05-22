<?php

use App\Http\Controllers\PublisherController;

Route::resource('publishers', PublisherController::class);

use App\Http\Controllers\AuthorController;

Route::resource('authors', AuthorController::class);

use App\Http\Controllers\CategoryController;

Route::resource('categories', CategoryController::class);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

