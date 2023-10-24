<?php

namespace App\Repositories;

use App\Models\ReservationsStop;
use App\Models\TripsSeat;


class TripSeatRepo
{
    /** get trip seats 
     * @param $tripId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static  function getTripSeats($tripId)
    {
        return TripsSeat::query()
            ->where('trip_id', '=', $tripId)
            ->with('reservations')
            ->get();
    }

   
    public static function getReservSeats($seatId, $needed_stations): bool
    {
        $var = !ReservationsStop::query()
            ->leftJoin(
                'customers_seats_reservations',
                'customers_seats_reservations.id',
                '=',
                'reservations_stops.reservation_id'
            )
            ->where('customers_seats_reservations.seat_id', '=', $seatId)
            ->whereIn('reservations_stops.city_id', $needed_stations)->count();

            return $var;
    }

    /** check a seat of a trip
     * @param $sid
     * @param $tid
     * @return bool
     */
    public static function checkSeatBelognToTrip($sid, $tid): bool
    {
        // seat validation
        if (!$seat = TripsSeat::find($sid))
            return false;

        // seat trip id
        if ($seat->trip_id == $tid)
            return true;

        return false;
    }
}
