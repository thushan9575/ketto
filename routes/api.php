<?php

use Illuminate\Support\Facades\Route;
use App\Rooms\Io\Http\Controllers\RoomsController;

Route::get('/rooms', [RoomsController::class, 'index']);
Route::post('/rooms', [RoomsController::class, 'store']);
Route::put('/rooms/{room}', [RoomsController::class, 'update']);
Route::delete('/rooms/{room}', [RoomsController::class, 'destroy']);