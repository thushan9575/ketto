<?php

namespace App\RoomTypes\Usecases;

use App\RoomTypes\Entities\Models\RoomType;

class ListRoomType
{
    public function execute()
    {
        return RoomType::all();
    }
}