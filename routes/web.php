<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public User Routes
Route::livewire('/', 'user.home')->name('home');

// Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'auth.login')->name('login');
});

// Admin Routes (Auth Protected)
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::livewire('/', 'admin.dashboard')->name('admin.dashboard');
    Route::livewire('/categories', 'admin.category-manager')->name('admin.categories');
    Route::livewire('/providers', 'admin.provider-manager')->name('admin.providers');

    // Logout Route
    Route::get('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
