<?php

namespace App\RoomTypes\UseCases;

use App\RoomTypes\Entities\Models\RoomType;

class UpdateRoomType
{
    public function execute(RoomType $roomType, array $data)
    {
        $roomType->update($data);
        return $roomType;
    }
}