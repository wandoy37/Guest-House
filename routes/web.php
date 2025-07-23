<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Services\RevenueService;

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

// Dashboard Controller
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/pendapatan/harian', [RevenueService::class, 'harian']);
Route::get('/pendapatan/mingguan', [RevenueService::class, 'mingguan']);
Route::get('/pendapatan/bulanan', [RevenueService::class, 'bulanan']);

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
// Show Update Booking
Route::get('/booking/show/{booking}', [BookingController::class, 'show'])->name('booking.show');
// Update Booking
Route::patch('/booking/show/{booking}/update', [BookingController::class, 'update'])->name('booking.update');
// Detail Booking
Route::get('/booking/detail/{booking}', [BookingController::class, 'detail'])->name('booking.detail');

// Check-Out Controller
Route::get('/checkout', [CheckOutController::class, 'index'])->name('check.out');
// Checkout Detail
Route::get('/checkout/show/{booking}', [CheckOutController::class, 'show'])->name('check.out.show');
// Checkout Proses
Route::patch('/checkout/booking/{booking}', [CheckOutController::class, 'checkoutBooking'])->name('check.out.booking');

// InvoiceController
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/invoice/show/{invoice:invoice_number}', [InvoiceController::class, 'show'])->name('invoice.show');

// ReportController
Route::get('/report/booking', [ReportController::class, 'bookingReport'])->name('report.booking.index');
Route::get('/report/booking/show/{report}', [ReportController::class, 'bookingReportShow'])->name('report.booking.show');
