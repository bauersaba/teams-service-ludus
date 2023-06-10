<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\TeamController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/getToken', function () {
    $tempUser = User::firstOrCreate(
        ['email' => 'temporary@example.com'],
        ['name' => 'Temporary User', 'password' => Hash::make('temporary-password')] 
    );

    $token = $tempUser->createToken('Temporary Token')->plainTextToken;
    return response()->json(['token' => $token]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('coach', CoachController::class);
    Route::resource('team', TeamController::class);
});
