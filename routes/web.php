<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvoiceController;
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

Route::get('/invoice', function () {
    return view('invoice', [
        'guesthouse' => [
            'name' => 'SiGH Guest House',
            'address' => 'Jl. Mawar No. 10, Samarinda',
            'email' => 'admin@sighgh.id',
            'phone' => '0812-3456-7890'
        ],
        'guest' => [
            'name' => 'Rasya',
            'address' => 'Jl. Melati No. 2',
            'email' => 'rasya@mail.com',
            'phone' => '0851-1234-5678'
        ],
        'invoice_number' => 'INV-20250715-001',
        'booking' => [
            'checkin' => '2025-07-14',
            'checkout' => '2025-07-16',
        ],
        'room' => [
            'class' => 'Standar Double Bed',
            'price' => 175000,
            'days' => 2
        ],
        'payment' => [
            'room_charge' => 175000 * 2,
            'deposit' => 50000,
            'total' => (175000 * 2) + 50000
        ]
    ]);
});

Route::resource('room', RoomController::class);
Route::resource('guest', GuestController::class);

// Check-In Controller
Route::get('/checkin', [CheckInController::class, 'index'])->name('check.in');
// Reservation
Route::get('/checkin/reservation/{room}', [CheckInController::class, 'reservation'])->name('check.in.reservation');
// Reservation Store
Route::post('/checkin/reservation', [CheckInController::class, 'store'])->name('check.in.store');

// BookingController
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
// Show Detail Booking
Route::get('/booking/show/{booking}', [BookingController::class, 'show'])->name('booking.show');
// Update Booking
Route::patch('/booking/show/{booking}/update', [BookingController::class, 'update'])->name('booking.update');

// Check-Out Controller
Route::get('/checkout', [CheckOutController::class, 'index'])->name('check.out');
// Checkout Detail
Route::get('/checkout/show/{booking}', [CheckOutController::class, 'show'])->name('check.out.show');
// Checkout Proses
Route::patch('/checkout/booking/{booking}', [CheckOutController::class, 'checkoutBooking'])->name('check.out.booking');

// InvoiceController
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/invoice/show/{invoice:invoice_number}', [InvoiceController::class, 'show'])->name('invoice.show');
