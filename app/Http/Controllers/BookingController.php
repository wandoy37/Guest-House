<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\BookingLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['guest', 'room', 'logs.user'])
            ->whereIn('status', ['checkin', 'checkout'])
            ->orderBy('checkin', 'desc')
            ->get();

        return view('booking.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['guest', 'room', 'logs.user'])
            ->where('id', $id)
            ->whereIn('status', ['checkin', 'checkout'])
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        $rooms = Room::where('status', 'available')
            ->orWhere('id', $booking->room_id) // Include current room even if not available
            ->get();
        $guests = Guest::all();
        return view('booking.show', compact('booking', 'rooms', 'guests'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi request
        $request->validate([
            'booking_id' => 'required|exists:bookings,id', // Tambahkan validasi booking_id
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
            'day' => 'required|numeric|min:1',
            'room_charge' => 'required|string',
            'deposit' => 'required|string',
            'total_payment' => 'required|string',
            'action' => 'required|string|in:update,cancel,change room', // Batasi nilai action
            'note' => 'required|string',
            'user_id' => 'required|exists:users,id', // Tambahkan validasi user_id
        ]);

        // Gunakan Database Transaction untuk keamanan
        DB::beginTransaction();

        try {
            // 2. Ambil data booking yang akan diupdate
            $booking = Booking::findOrFail($request->booking_id);
            $oldRoomId = $booking->room_id;

            // 3. Pengecekan status data booking
            $valueStatus = ($request->action == 'cancel') ? 'cancel' : 'checkin';

            // Parse angka dari input (format rupiah)
            $roomCharge = (int) str_replace('.', '', $request->room_charge);
            $deposit = (int) str_replace('.', '', $request->deposit);
            $totalPayment = (int) str_replace('.', '', $request->total_payment);

            // 4. Ambil value untuk data booking
            $dataBooking = [
                'room_id' => $request->room_id,
                'guest_id' => $request->guest_id,
                'checkin' => $request->checkin,
                'checkout' => $request->checkout,
                'day' => $request->day,
                'room_charge' => $roomCharge,
                'deposit' => $deposit,
                'total_payment' => $totalPayment,
                'status' => $valueStatus,
            ];

            // 5. Handle perubahan room_id SEBELUM update booking
            if ($request->room_id != $oldRoomId) {
                // Cek apakah room baru tersedia (kecuali jika cancel)
                if ($request->action !== 'cancel') {
                    $newRoom = Room::where('id', $request->room_id)
                        ->where('status', 'available')
                        ->first();

                    if (!$newRoom) {
                        throw new Exception('Room yang dipilih tidak tersedia');
                    }
                }

                // Update status room lama menjadi available
                Room::where('id', $oldRoomId)->update(['status' => 'available']);

                // Update status room baru (kecuali jika cancel)
                if ($request->action !== 'cancel') {
                    Room::where('id', $request->room_id)->update(['status' => 'checkin']);
                }
            }

            // 6. Handle khusus untuk action cancel
            if ($request->action == 'cancel') {
                // Pastikan room menjadi available (jika belum diubah di step 5)
                if ($request->room_id == $oldRoomId) {
                    Room::where('id', $request->room_id)->update(['status' => 'available']);
                }
            }

            // 7. Update data booking
            $booking->update($dataBooking);

            // 8. Catat log booking
            $dataLog = [
                'booking_id' => $request->booking_id,
                'user_id' => $request->user_id,
                'action' => $request->action,
                'description' => $request->note,
            ];

            BookingLog::create($dataLog);

            // Commit transaction
            DB::commit();

            return redirect()->route('booking.index')->with('success', 'Booking berhasil diupdate');
        } catch (Exception $e) {
            // Rollback jika ada error
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal update booking: ' . $e->getMessage())->withInput();
        }
    }
}
