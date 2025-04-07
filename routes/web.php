<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreateTricketController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\CreateTricketController::class, 'index'])->name('dashboard');

// tricket
Route::get('/create-tricket', [App\Http\Controllers\CreateTricketController::class, 'create'])->name('etricket.create');

Route::resource('etricket', CreateTricketController::class);
Route::post('etricket/{ticket}/reply', [CreateTricketController::class, 'addReply'])->name('etricket.addReply');
Route::post('etricket/{ticket}/attachment', [CreateTricketController::class, 'addAttachment'])->name('etricket.addAttachment');
