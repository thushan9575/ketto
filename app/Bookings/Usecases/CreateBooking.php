<?php
namespace App\Bookings\UseCases;

use App\Bookings\Entities\Models\Booking;

use Illuminate\Validation\ValidationException;




class CreateBooking
{
    public function execute(array $data)
    {
        // ❌ Check overlapping bookings
        $exists = Booking::where('room_id', $data['room_id'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('arrival', [$data['arrival'], $data['departure']])
                  ->orWhereBetween('departure', [$data['arrival'], $data['departure']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('arrival', '<=', $data['arrival'])
                         ->where('departure', '>=', $data['departure']);
                  });
            })
            ->exists();

        if ($exists) {
            abort(422, 'Room already booked for selected dates');
        }

        return Booking::create($data);
    }
}