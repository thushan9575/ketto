<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Inertia\Inertia;

// Home
Route::inertia('/', 'Dashboard', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

// USER DASHBOARD
Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('welcome');
});

// ADMIN DASHBOARD
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::inertia('admin/dashboard', 'Admin/Dashboard')->name('admin.dashboard');
});

require __DIR__.'/settings.php';