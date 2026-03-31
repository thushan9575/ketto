<?php
namespace App\Bookings\Io\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Bookings\Usecases\CreateBooking;
use App\Bookings\Usecases\Request\CreateBookingRequest;
use App\Bookings\Entities\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    // ✅ GET BOOKED DATES
    public function bookedDates($roomId)
    {
        $bookings = Booking::where('room_id', $roomId)
            ->get(['arrival', 'departure']);

        return response()->json($bookings);
    }

    // ✅ GET ALL BOOKINGS (Admin)
    public function index()
    {
        return Booking::with('room')->latest()->get();
    }

    // ✅ DELETE BOOKING
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }


public function stats()
{
    $bookings = Booking::with('room')->get();

   
    $totalBookings = $bookings->count();

    
    $totalUsers = User::count();

   
    $revenue = $bookings->sum(function ($b) {
        $days = Carbon::parse($b->arrival)
            ->diffInDays(Carbon::parse($b->departure));

        return $days * ($b->room->price ?? 0);
    });

    
    $monthlyRevenue = Booking::with('room')
        ->select(
            DB::raw('MONTH(arrival) as month'),
            DB::raw('YEAR(arrival) as year')
        )
        ->get()
        ->groupBy(fn($b) => $b->year . '-' . $b->month)
        ->map(function ($group) {
            return $group->sum(function ($b) {
                $days = Carbon::parse($b->arrival)
                    ->diffInDays(Carbon::parse($b->departure));

                return $days * ($b->room->price ?? 0);
            });
        });

    return response()->json([
        'totalUsers' => $totalUsers,
        'totalBookings' => $totalBookings,
        'revenue' => $revenue,
        'monthlyRevenue' => $monthlyRevenue
    ]);
}
}

