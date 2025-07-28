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
use App\Http\Controllers\UserController;
use App\Services\RevenueService;
use Illuminate\Support\Facades\Artisan;
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
    return redirect()->route('login');
});

Route::middleware(['auth', 'role:admin,manager,receptionist'])->group(function () {
    // Dashboard Controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // Guest Controller
    Route::resource('guest', GuestController::class);
});

Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    // Room Controller
    Route::resource('room', RoomController::class);

    // User Controller
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // ReportController
    Route::get('/report/booking', [ReportController::class, 'bookingReport'])->name('report.booking.index');
    Route::get('/report/booking/show/{report}', [ReportController::class, 'bookingReportShow'])->name('report.booking.show');
    // Revenue Report
    Route::get('/report/revenue', [ReportController::class, 'revenueReport'])->name('report.revenue');
    // Guest Report
    Route::get('/report/guest', [ReportController::class, 'guestReport'])->name('guest.revenue');
    // Invoice Report
    Route::get('/report/invoice', [ReportController::class, 'invoiceReport'])->name('invoice.revenue');
});

Route::middleware(['auth', 'role:admin,receptionist'])->group(function () {
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
});

Route::get('/invoice/show/{invoice:invoice_number}', [InvoiceController::class, 'show'])->name('invoice.show');
