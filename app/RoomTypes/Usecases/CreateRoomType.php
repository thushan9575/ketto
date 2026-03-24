<?php

namespace App\RoomTypes\UseCases;

use App\RoomTypes\Entities\Models\RoomType;

class CreateRoomType
{
    public function execute(array $data)
    {
        return RoomType::create($data);
    }
}