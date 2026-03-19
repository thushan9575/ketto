<?php
namespace App\Rooms\UseCases;

use App\Rooms\Entities\Models\Room;


class DeleteRoom
{
    public function execute(Room $room)
    {
        $room->delete();
        return true;
    }
}