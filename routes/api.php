<?php

use App\Http\Controllers\Api\ReservationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', function (Request $request) {

    if (!auth()->attempt($request->only(['email', 'password']))) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    
    return response()->json([
        "success" => true,
        "token" => auth()->user()->createToken('')->plainTextToken
    ]);
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/bookings', [ReservationsController::class, 'bookSeat']);
    Route::get('/available-seats', [ReservationsController::class, 'getAvailableSeats']);
});
