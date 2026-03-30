<?php
namespace App\Rooms\Entities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Rooms\Io\Database\Factories\RoomFactory;


class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_title',
        'image',
        'description',
        'price',
        'wifi',
        'room_type_id' 
    ];

    protected static function newFactory()
    {
        return RoomFactory::new();
    }
}