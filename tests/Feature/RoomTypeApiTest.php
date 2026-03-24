<?php

use App\RoomTypes\Entities\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a room type', function () {
    $payload = ['name' => 'Presidential Suite'];

    $this->postJson('/api/room-types', $payload)
        ->assertStatus(201)
        ->assertJsonFragment(['name' => 'Presidential Suite']);

    $this->assertDatabaseHas('room_types', ['name' => 'Presidential Suite']);
});

test('can list room types', function () {
    RoomType::factory()->count(3)->create();

    $this->getJson('/api/room-types')
        ->assertOk()
        ->assertJsonCount(3);
});

test('can update a room type', function () {
    $roomType = RoomType::factory()->create();

    $this->putJson("/api/room-types/{$roomType->id}", ['name' => 'Updated Type'])
        ->assertOk()
        ->assertJsonFragment(['name' => 'Updated Type']);

    $this->assertDatabaseHas('room_types', ['name' => 'Updated Type']);
});

test('can delete a room type', function () {
    $roomType = RoomType::factory()->create();

    $this->deleteJson("/api/room-types/{$roomType->id}")
        ->assertOk();

    $this->assertDatabaseMissing('room_types', ['id' => $roomType->id]);
});