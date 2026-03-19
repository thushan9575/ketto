<?php
namespace App\Rooms\UseCases;

use App\Rooms\Entities\Models\Room;

class ListRooms
{
    public function execute()
    {
        return Room::all();
    }
}