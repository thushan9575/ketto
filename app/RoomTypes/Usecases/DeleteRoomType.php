<?php

namespace App\RoomTypes\UseCases;

use App\RoomTypes\Entities\Models\RoomType;

class DeleteRoomType
{
    public function execute(RoomType $roomType)
    {
        $roomType->delete();
    }
}