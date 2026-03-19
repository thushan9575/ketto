<?php
namespace App\Rooms\UseCases;

use App\Rooms\Entities\Models\Room;

class CreateRoom
{
    public function execute(array $data)
    {
        return Room::create($data);
    }
}