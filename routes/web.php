<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('room', RoomController::class);
Route::resource('guest', GuestController::class);

// Check-In Controller
Route::get('/checkin', [CheckInController::class, 'index'])->name('check.in');
// Reservation
Route::get('/checkin/reservation/{room}', [CheckInController::class, 'reservation'])->name('check.in.reservation');
// Reservation Store
Route::post('/checkin/reservation', [CheckInController::class, 'store'])->name('check.in.store');

// Check-Out Controller
Route::get('/checkout', [CheckOutController::class, 'index'])->name('check.out');

// BookingController
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
// Show Detail Booking
Route::get('/booking/show/{booking}', [BookingController::class, 'show'])->name('booking.show');
// Update Booking
Route::patch('/booking/show/{booking}/update', [BookingController::class, 'update'])->name('booking.update');
