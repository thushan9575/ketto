<?php

use Illuminate\Support\Facades\Route;
use App\Rooms\Io\Http\Controllers\RoomsController;
use App\RoomTypes\Io\Http\Controllers\RoomTypesController;
use App\Bookings\Io\Http\Controllers\BookingController;

// ROOMS
Route::get('/rooms', [RoomsController::class, 'index']);
Route::post('/rooms', [RoomsController::class, 'store']);
Route::put('/rooms/{room}', [RoomsController::class, 'update']);
Route::delete('/rooms/{room}', [RoomsController::class, 'destroy']);

// ROOM TYPES
Route::get('/room-types', [RoomTypesController::class, 'index']);
Route::post('/room-types', [RoomTypesController::class, 'store']);
Route::put('/room-types/{room_type}', [RoomTypesController::class, 'update']);
Route::delete('/room-types/{room_type}', [RoomTypesController::class, 'destroy']);

// BOOKINGS
Route::post('/bookings', [BookingController::class, 'store']);

// ✅ ADD THIS (VERY IMPORTANT)
Route::get('/rooms/{room}/booked-dates', [BookingController::class, 'bookedDates']);

// ✅ NEW (Admin)
Route::get('/bookings', [BookingController::class, 'index']);
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
Route::get('/admin/stats', [BookingController::class, 'stats']);