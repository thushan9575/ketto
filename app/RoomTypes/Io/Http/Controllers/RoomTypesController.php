<?php

namespace App\RoomTypes\Io\Http\Controllers;


use App\Http\Controllers\Controller;
use App\RoomTypes\Entities\Models\RoomType;
use App\RoomTypes\Usecases\Request\RoomTypeRequest;
use App\RoomTypes\UseCases\CreateRoomType;
use App\RoomTypes\UseCases\UpdateRoomType;
use App\RoomTypes\UseCases\DeleteRoomType;
use App\RoomTypes\UseCases\ListRoomType;

class RoomTypesController extends Controller
{
    public function index(ListRoomType $listRoomType)
    {
        return response()->json($listRoomType->execute());
    }

    public function store(RoomTypeRequest $request, CreateRoomType $createRoomType)
    {
        $roomType = $createRoomType->execute($request->validated());
        return response()->json($roomType, 201);
    }

    public function update(RoomTypeRequest $request, RoomType $room_type, UpdateRoomType $updateRoomType)
    {
        return response()->json($updateRoomType->execute($room_type, $request->validated()));
    }

    public function destroy(RoomType $room_type, DeleteRoomType $deleteRoomType)
    {
        $deleteRoomType->execute($room_type);
        return response()->json(['message' => 'Deleted']);
    }
}