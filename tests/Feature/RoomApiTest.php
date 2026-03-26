<?php
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\RoomTypes\Entities\Models\RoomType;


use App\Rooms\Entities\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('lists all rooms', function () {

    Room::factory()->count(3)->create();

    $this->getJson('/api/rooms')
        ->assertOk()
        ->assertJsonCount(3);
});

test('creates a new room', function () {

    Storage::fake('public');

    // ✅ CREATE VALID ROOM TYPE FIRST
    $roomType = RoomType::factory()->create();

    $payload = [
        'room_title' => 'Deluxe Room',
        'image' => UploadedFile::fake()->image('room.jpg'),
        'description' => 'Nice room',
        'price' => 2500,
        'wifi' => true,
        'room_type_id' => $roomType->id // ✅ FIX
    ];

    $this->post('/api/rooms', $payload)
        ->assertStatus(201)
        ->assertJsonFragment([
            'room_title' => 'Deluxe Room'
        ]);

    $this->assertDatabaseHas('rooms', [
        'room_title' => 'Deluxe Room',
        'price' => 2500
    ]);

   
});

test('updates a room', function () {

    $room = Room::factory()->create();

    $this->putJson("/api/rooms/{$room->id}", [
        'room_title' => 'Updated Room'
    ])
        ->assertOk()
        ->assertJsonFragment(['room_title' => 'Updated Room']);

    $this->assertDatabaseHas('rooms', [
        'id' => $room->id,
        'room_title' => 'Updated Room'
    ]);
});

test('deletes a room', function () {

    $room = Room::factory()->create();

    $this->deleteJson("/api/rooms/{$room->id}")
        ->assertOk();

    $this->assertDatabaseMissing('rooms', [
        'id' => $room->id
    ]);
});

test('validates required fields', function () {

    $this->postJson('/api/rooms', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'room_title',
            'description',
            'price',
            'room_type_id'
        ]);
});

test('validates price must be numeric', function () {

    $this->postJson('/api/rooms', [
        'room_title' => 'Test',
        'description' => 'Test desc',
        'price' => 'invalid',
        'room_type_id' => 1
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['price']);
});

