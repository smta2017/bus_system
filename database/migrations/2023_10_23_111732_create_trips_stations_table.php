<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * this is apivot table
     * @return void
     */
    public function up()
    {
        Schema::create('trips_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->references('id')->on('trips');
            $table->foreignId('city_id')->references('id')->on('cities');
            $table->integer('station_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips_stations');
    }
};
