<?php
namespace App\Bookings\Io\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Bookings\UseCases\CreateBooking;
use App\Bookings\UseCases\Request\CreateBookingRequest;
use App\Bookings\Entities\Models\Booking;

class BookingController extends Controller
{
    // ✅ CREATE BOOKING
    public function store(CreateBookingRequest $request, CreateBooking $createBooking)
    {
        return response()->json(
            $createBooking->execute($request->validated()),
            201
        );
    }

    // ✅ GET BOOKED DATES (IMPORTANT FOR FRONTEND)
    public function bookedDates($roomId)
    {
        $bookings = Booking::where('room_id', $roomId)
            ->get(['arrival', 'departure']);

        return response()->json($bookings);
    }

    // ✅ NEW: GET ALL BOOKINGS (Admin)
    public function index()
    {
        return Booking::with('room')->latest()->get();
    }

    // ✅ NEW: DELETE BOOKING (Admin)
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }
    
}