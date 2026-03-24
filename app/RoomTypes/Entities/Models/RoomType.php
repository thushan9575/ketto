<?php

namespace App\RoomTypes\Entities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Rooms\Entities\Models\Room;
use App\RoomTypes\Io\Database\Factories\RoomTypeFactory;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // ✅ Correct relationship
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // ✅ Bind custom factory
    protected static function newFactory()
    {
        return RoomTypeFactory::new();
    }
}