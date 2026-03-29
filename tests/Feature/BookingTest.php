<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Rooms\Entities\Models\Room;
use App\RoomTypes\Entities\Models\RoomType;

class BookingTest extends TestCase
{
    use RefreshDatabase; // ✅ MUST HAVE

    public function test_user_can_book_room()
    {
        // create room type
        $type = RoomType::create([
            'name' => 'Deluxe'
        ]);

        // create room
        $room = Room::create([
            'room_title' => 'Test Room',
            'description' => 'Nice room',
            'price' => 1000,
            'wifi' => true,
            'room_type_id' => $type->id
        ]);

        // send booking request
        $response = $this->postJson('/api/bookings', [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'arrival' => '2026-04-01',
            'departure' => '2026-04-05',
            'room_id' => $room->id
        ]);

        $response->assertStatus(201);
    }
}