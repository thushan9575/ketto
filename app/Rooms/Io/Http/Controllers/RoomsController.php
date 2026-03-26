<?php
namespace App\Rooms\Io\Http\Controllers;

use App\Rooms\Entities\Models\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rooms\UseCases\CreateRoom;
use App\Rooms\UseCases\ListRooms;
use App\Rooms\UseCases\UpdateRoom;
use App\Rooms\UseCases\DeleteRoom;

class RoomsController extends Controller
{
    public function index(ListRooms $listRooms)
    {
        return response()->json($listRooms->execute());
    }

    public function store(Request $request, CreateRoom $createRoom)
    {
        $data = $request->validate([
            'room_title' => 'required',
            'image' => 'nullable|image',
            'description' => 'required',
            'price' => 'required|numeric',
            'wifi' => 'boolean',
            'room_type_id' => 'required|exists:room_types,id' 
        ]);
    
        // ✅ STORE IMAGE PROPERLY
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rooms', 'public');
            $data['image'] = $path; // e.g. rooms/abc123.jpg
        }
    
        return response()->json($createRoom->execute($data), 201);
    }

    public function update(Request $request, Room $room, UpdateRoom $updateRoom)
    {
        $data = $request->validate([
            'room_title' => 'sometimes|required',
            'image' => 'nullable|image',
            'description' => 'sometimes|required',
            'price' => 'sometimes|numeric',
            'wifi' => 'boolean',
            'room_type_id' => 'sometimes|required|exists:room_types,id' 
        ]);

        return response()->json($updateRoom->execute($room, $data));
    }

    public function destroy(Room $room, DeleteRoom $deleteRoom)
    {
        $deleteRoom->execute($room);
        return response()->json(['message' => 'Deleted']);
    }
}