<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.home.index');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::middleware('auth')->group(function () {
        Route::get('/', function () {
            return view('admin.home.index');
        })->name('admin.dashboard');
    });

    // category routes
    Route::middleware('auth')->group(function () {
        Route::get('/categories', function () {
            return view('admin.category.index');
        })->name('admin.categories');
    });

    // prodiver routes
    Route::middleware('auth')->group(function () {
        Route::get('/providers', function () {
            return view('admin.provider.index');
        })->name('admin.providers');
    });

    // settings routes
    Route::middleware('auth')->group(function () {
        Route::get('/settings', function () {
            return view('admin.setting.index');
        })->name('admin.settings');
    });

    // profile routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', function () {
            return view('admin.profile.index');
        })->name('admin.profile');
    });

    // logout route
    Route::get('/logout', function () {
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');
});
