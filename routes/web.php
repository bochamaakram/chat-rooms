<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\MessageController;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('rooms.index');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('rooms', RoomController::class)->except(['edit', 'update']);

    // Join room routes
    Route::get('/join-room', [RoomController::class, 'showJoinForm'])->name('rooms.join.form');
    Route::post('/join-room', [RoomController::class, 'join'])->name('rooms.join');

    // Messages
    Route::post('/rooms/{room}/messages', [MessageController::class, 'store'])->name('messages.store');
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset Routes
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');