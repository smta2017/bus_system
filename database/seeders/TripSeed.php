<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\City;
use App\Models\Trip;
use App\Models\TripsSeat;
use App\Models\TripsStation;
use Illuminate\Database\Seeder;

class TripSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cities = ['Cairo', 'Giza', 'AlFayyum', 'AlMinya', 'Asyut'];

        foreach ($cities as  $value) {
            \App\Models\City::factory()->create(['name' => $value]);
        }

        $bus = Bus::factory()->create([
            'name' => 'Cairo Bus',
        ]);


        $trip = Trip::factory()->create([
            'name' => 'Cairo asyot',
            'bus_id' => $bus->id
        ]);

        for ($i = 0; $i < 12; $i++) {
            TripsSeat::factory()->create([
                'trip_id' => 1
            ]);
        }

        $cities = City::all();
        foreach ($cities as $key => $city) {
            TripsStation::factory()->create([
                'trip_id' => $trip->id,
                'city_id' => $city->id,
                'station_order' => $key
            ]);
        }
    }
}
