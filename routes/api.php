<?php

use Illuminate\Support\Facades\Route;
use App\Rooms\Io\Http\Controllers\RoomsController;
use App\RoomTypes\Io\Http\Controllers\RoomTypesController;
Route::get('/rooms', [RoomsController::class, 'index']);
Route::post('/rooms', [RoomsController::class, 'store']);
Route::put('/rooms/{room}', [RoomsController::class, 'update']);
Route::delete('/rooms/{room}', [RoomsController::class, 'destroy']);

Route::get('/room-types', [RoomTypesController::class, 'index']);
Route::post('/room-types', [RoomTypesController::class, 'store']);
Route::put('/room-types/{room_type}', [RoomTypesController::class, 'update']);
Route::delete('/room-types/{room_type}', [RoomTypesController::class, 'destroy']);