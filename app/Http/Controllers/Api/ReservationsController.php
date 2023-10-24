<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ReservationRepo;
use App\Repositories\TripRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    public $reservationRepo;
    public function __construct()
    {
        $this->reservationRepo = new ReservationRepo(new TripRepo());
    }

    public function getAvailableSeats(Request $request)
    {
        $trip = (int)$request->trip_id;
        $from = (int)$request->from_city_id;
        $to = (int)$request->to_city_id;

        $seats_ids = $this->reservationRepo->getAvailableSeatsOfTrip($trip, $from, $to);

        return response()->json([
            'success' => true,
            'data' => $seats_ids
        ]);
    }

    public function bookSeat(Request $request)
    {
        $trip = (int)$request->trip_id;
        $seat = (int)$request->seat_id;
        $from = (int)$request->from_city_id;
        $to = (int)$request->to_city_id;

        $user_id = Auth::id() ?? 1;

        $is_saved =  $this->reservationRepo->bookSeat($trip, $seat, $from, $to, $user_id);

        $message = $is_saved ? "Done" : "Not complete";

        $status = $user_id ? 200 : 422;
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_saved' => $is_saved
        ], $status);
    }
}
