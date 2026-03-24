<?php


namespace App\RoomTypes\Io\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\RoomTypes\Entities\Models\RoomType;

class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Single', 'Double', 'Deluxe']),
        ];
    }
}