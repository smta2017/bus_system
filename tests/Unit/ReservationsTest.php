<?php

namespace Tests\Feature\Reservations;

use App\Models\Bus;
use App\Models\City;
use App\Models\Trip;
use App\Models\TripsStation;
use App\Models\User;
use App\Repositories\ReservationRepo;
use App\Repositories\TripRepo;


use Tests\TestCase;

class ReservationsTest extends TestCase
{
    protected $reservationService;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->reservationService = new ReservationRepo(new TripRepo());


        parent::__construct($name, $data, $dataName);
    }

 
    public function test_available_seats()
    {

        $from = \App\Models\City::factory()->create(['name' => 'Cairo']);
        $in = \App\Models\City::factory()->create(['name' => 'AlMinya']);
        $to = \App\Models\City::factory()->create(['name' => 'Asyut']);


        $bus = Bus::factory()->create([
            'name' => 'Cairo Bus',
        ]);


        $trip = Trip::factory()->create([
            'name' => 'Cairo Asyut Trip',
            'bus_id' => $bus->id
        ]);


        $cities = City::all();
        foreach ($cities as $key => $city){
            TripsStation::factory()->create([
                'trip_id' => $trip->id,
                'city_id' => $city->id,
                'station_order' => $key
            ]);
        }


        $seets = $trip->seats;

        $this->assertEquals(
            count($this->reservationService->getAvailableSeatsOfTrip($trip->id, $from->id, $to->id)),
            $bus->seats_capacity);

        $user = User::factory()->create();

        $this->reservationService->bookSeat($trip->id, $seets[2]->id, $from->id, $to->id, $user->id);

        $this->assertEquals(
            count($this->reservationService->getAvailableSeatsOfTrip($trip->id, $from->id, $to->id)),
            $bus->seats_capacity - 1);


            $this->reservationService->bookSeat($trip->id, $seets[2]->id, $from->id, $in->id, $user->id);


        $this->assertEquals(
            count($this->reservationService->getAvailableSeatsOfTrip($trip->id, $in->id, $to->id)),
            $bus->seats_capacity - 1);

    }


    public function test_book_set()
    {

        $from = \App\Models\City::factory()->create(['name' => 'Cairo']);
        $in = \App\Models\City::factory()->create(['name' => 'AlMinya']);
        $to = \App\Models\City::factory()->create(['name' => 'Asyut']);


        $bus = Bus::factory()->create([
            'name' => 'Cairo Bus',
        ]);


        $trip = Trip::factory()->create([
            'name' => 'Cairo Asyut Trip',
            'bus_id' => $bus->id
        ]);


        $cities = City::all();
        foreach ($cities as $key => $city){
            TripsStation::factory()->create([
                'trip_id' => $trip->id,
                'city_id' => $city->id,
                'station_order' => $key
            ]);
        }


        $seets = $trip->seats;



        
        $this->assertEquals(
            count($this->reservationService->getAvailableSeatsOfTrip($trip->id, $from->id, $to->id)),
            $bus->seats_capacity);

        $user = User::factory()->create();

        $this->assertTrue($this->reservationService->bookSeat($trip->id, $seets[2]->id, $from->id, $to->id, $user->id));


        $this->assertFalse($this->reservationService->bookSeat($trip->id, $seets[2]->id, $from->id, $to->id, $user->id));



        $this->assertTrue($this->reservationService->bookSeat($trip->id, $seets[1]->id, $from->id, $in->id, $user->id));
        $this->assertTrue($this->reservationService->bookSeat($trip->id, $seets[1]->id, $in->id, $to->id, $user->id));



        

        $this->assertTrue($this->reservationService->bookSeat($trip->id, $seets[0]->id, $in->id, $to->id, $user->id));
        $this->assertTrue($this->reservationService->bookSeat($trip->id, $seets[0]->id, $from->id, $in->id, $user->id));
    }
}
