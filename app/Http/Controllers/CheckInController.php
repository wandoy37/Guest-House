<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status'); // Ambil parameter status dari query string

        if ($status && in_array($status, ['available', 'checkin', 'checkout'])) {
            // Filter berdasarkan status jika parameter ada dan valid
            $rooms = Room::where('status', $status)->get();
        } else {
            // Tampilkan semua data jika tidak ada filter atau filter = 'all'
            $rooms = Room::all();
        }

        return view('checkin.index', compact('rooms', 'status'));
    }

    public function reservation($id)
    {
        $room = Room::find($id);
        $guests = Guest::all();
        return view('checkin.reservation', compact('room', 'guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
            'day' => 'required|numeric|min:1',
            'room_charge' => 'required|string',
            'deposit' => 'required|string',
            'total_payment' => 'required|string',
        ]);

        // Parse angka dari input (format rupiah)
        $roomCharge = (int) str_replace('.', '', $request->room_charge);
        $deposit = (int) str_replace('.', '', $request->deposit);
        $totalPayment = (int) str_replace('.', '', $request->total_payment);

        // Simpan ke tabel bookings
        $booking = Booking::create([
            'guest_id' => $request->guest_id,
            'room_id' => $request->room_id,
            'checkin' => $request->checkin,
            'checkout' => $request->checkout,
            'day' => $request->day,
            'room_charge' => $roomCharge,
            'deposit' => $deposit,
            'total_payment' => $totalPayment,
            'status' => 'checkin',
        ]);

        // Catat ke booking_logs
        BookingLog::create([
            'booking_id' => $booking->id,
            'user_id' => $request->user_id,
            'action' => 'checkin',
            'description' => 'guest checkin',
        ]);

        // Update Status Room
        $room = Room::find($request->room_id);
        $room->status = 'checkin';
        $room->save();

        return redirect()->route('check.in')->with('success', 'Check-in was successfully saved.');
    }
}
