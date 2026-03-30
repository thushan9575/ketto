<?php
namespace App\Bookings\Usecases;

use App\Bookings\Entities\Models\Booking;

use Illuminate\Validation\ValidationException;



class CreateBooking
{
    public function execute(array $data)
    {
        // ✅ FIXED overlap logic (NO wrong dates)
        $exists = Booking::where('room_id', $data['room_id'])
            ->where(function ($q) use ($data) {
                $q->where('arrival', '<', $data['departure'])
                  ->where('departure', '>', $data['arrival']);
            })
            ->exists();

        if ($exists) {
            abort(422, 'Room already booked for selected dates');
        }

        return Booking::create($data);
    }
}
