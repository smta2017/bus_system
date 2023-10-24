<?php

namespace App\Repositories;

use App\Models\TripsStation;

class TripRepo
{

    /**  checking
     * @param $tripId
     * @param $fromCityId
     * @param $toCityId
     * @return bool
     */
    public function getTripStops($tripId, $fromCityId, $toCityId): array
    {



        $trip_stations = $this->tripStationsSorted($tripId);


        $from = array_search($fromCityId, $trip_stations);
        $to = array_search($toCityId, $trip_stations);


        return array_slice($trip_stations, $from, $to - $from);
    }


    /** get the trip route
     * @param $tripId
     * @return array
     */
    public function tripStationsSorted($tripId): array
    {
        return TripsStation::query()
            ->where('trip_id', '=', $tripId)
            ->orderBy('station_order')
            ->pluck('city_id')
            ->toArray();
    }
}
