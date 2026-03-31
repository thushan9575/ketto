<?php

namespace App\RoomTypes\Usecases;

use App\RoomTypes\Entities\Models\RoomType;

class DeleteRoomType
{
    public function execute(RoomType $roomType)
    {
        $roomType->delete();
    }
}