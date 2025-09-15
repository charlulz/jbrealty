<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PropertyPacketController;

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

/*
|--------------------------------------------------------------------------
| Property Packet API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('property-packet')->group(function () {
    // Request property packet (public endpoint with CSRF protection)
    Route::post('/', [PropertyPacketController::class, 'requestPacket'])
        ->middleware(['web', 'throttle:5,1'])
        ->name('api.property-packet.request');
    
    // Get packet status (for tracking/debugging)
    Route::get('/status', [PropertyPacketController::class, 'getPacketStatus'])
        ->middleware(['web', 'throttle:10,1'])
        ->name('api.property-packet.status');
});

/*
|--------------------------------------------------------------------------
| Additional API Endpoints
|--------------------------------------------------------------------------
*/

// Add more API routes here as needed
