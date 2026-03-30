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
    Route::inertia('/admin/rooms', 'Admin/Rooms/Index')->name('admin.rooms.index');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::inertia('admin/dashboard', 'Admin/Dashboard')->name('admin.dashboard');
    Route::inertia('/admin/room-types', 'Admin/RoomTypes/Index')->name('admin.roomtypes.index');
    Route::inertia('/admin/bookings', 'Admin/Bookings')->name('admin.bookings.index');
});

Route::get('/booking', function () {
    return Inertia::render('BookingPage');
});


require __DIR__.'/settings.php';