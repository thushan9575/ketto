<?php

namespace App\Rooms\Io\Database\Factories;

use App\Rooms\Entities\Models\Room;
use App\RoomTypes\Entities\Models\RoomType; // ✅ IMPORTANT
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'room_title' => $this->faker->words(2, true),
            'image' => 'room.jpg',
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(1000, 5000),
            'wifi' => $this->faker->boolean(),

            // ✅ FIX: Automatically create RoomType
            'room_type_id' => RoomType::factory(),
        ];
    }
}