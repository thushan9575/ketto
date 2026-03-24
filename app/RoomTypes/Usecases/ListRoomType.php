<?php

namespace App\RoomTypes\UseCases;

use App\RoomTypes\Entities\Models\RoomType;

class ListRoomType
{
    public function execute()
    {
        return RoomType::all();
    }
}