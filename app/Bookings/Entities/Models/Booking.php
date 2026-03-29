<?php

namespace App\Bookings\Entities\Models;

use Illuminate\Database\Eloquent\Model;
use App\Rooms\Entities\Models\Room;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'email',
        'arrival',
        'departure',
        'room_id'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}