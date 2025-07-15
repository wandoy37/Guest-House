<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOutController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('status', 'checkin')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('checkout.index', compact('bookings'));
    }

    public function show($id)
    {
        // 1. Ambil data booking dengan id tersebut
        $booking = Booking::find($id);

        return view('checkout.show', compact('booking'));
    }

    public function checkoutBooking($id)
    {
        try {
            // 1. Ambil data booking dengan id tersebut
            $booking = Booking::find($id);

            // 2. Validasi apakah booking ditemukan
            if (!$booking) {
                return redirect()->route('check.out')->with('error', 'Booking not found.');
            }

            // 3. Validasi apakah booking bisa di-checkout (status harus 'checkin')
            if ($booking->status !== 'checkin') {
                return redirect()->route('check.out')->with('error', 'Cannot checkout. Guest is not checked in.');
            }

            // 4. Update status booking menjadi checkout
            $booking->update(['status' => 'checkout']);

            // 5. Update status room menjadi available
            Room::where('id', $booking->room_id)->update(['status' => 'available']);

            // 6. Buat log Booking terkait proses checkout
            BookingLog::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::user()->id,
                'action' => 'checkout',
                'description' => 'Guest checkout completed successfully',
            ]);

            return redirect()->route('check.out')->with('success', 'Guest checkout completed. The room is now ready for the next guest.');
        } catch (\Exception $e) {
            return redirect()->route('check.out')->with('error', 'An error occurred during checkout: ' . $e->getMessage());
        }
    }
}
