<?php
namespace App\Rooms\UseCases;

use App\Rooms\Entities\Models\Room;


class UpdateRoom
{
    public function execute(Room $room, array $data)
    {
        $room->update($data);
        return $room;
    }
}