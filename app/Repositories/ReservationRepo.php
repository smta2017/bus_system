<?php

namespace App\Repositories;

use App\Models\Reservations;
use App\Models\ReservationsStop;
use Illuminate\Support\Facades\DB;

class ReservationRepo
{

    /**
     * @param TripRepo $tripRepo
     */
    public function __construct(protected TripRepo $tripRepo)
    {
    }

    /** available seats
     * @param $tripId
     * @param $fromCityId
     * @param $toCityId
     * @return array
     */
    public function getAvailableSeatsOfTrip($tripId, $fromCityId, $toCityId): array
    {


        $trip_seats = TripSeatRepo::getTripSeats($tripId);


        $stations = $this->tripRepo->getTripStops($tripId, $fromCityId, $toCityId);


        if (empty($stations)) {

            return array();
        }

        $seats = array();
        foreach ($trip_seats as $seat) {

            if (TripSeatRepo::getReservSeats($seat->id, $stations))
                $seats[] = $seat->id;
        }

        return  $seats;
    }

    //create reservation

    public function bookSeat($tripId, $seatId, $fromCityId, $toCityId, $user_id): bool
    {


        $stops = $this->tripRepo->getTripStops($tripId, $fromCityId, $toCityId);


        if (empty($stops)) {

            return array();
        }


        if (!TripSeatRepo::getReservSeats($seatId, $stops)){

            return false;
        }


        DB::beginTransaction();

        // new reservation
        $reservation = new Reservations();
        $reservation->user_id = $user_id;
        $reservation->seat_id = $seatId;

        try {

            // check saving
            if ($reservation->save()) {
                foreach ($stops as $cityId) {
                    ReservationsStop::create([
                        'reservation_id' => $reservation->id,
                        'city_id' => $cityId,
                    ]);
                }

                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }
}
