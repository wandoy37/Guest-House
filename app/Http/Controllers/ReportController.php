<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // 1. Booking Report
    public function bookingReport(Request $request)
    {
        // Logic for generating booking report description
        // Menampilkan jumlah dan detail tamu yang checkin dan checkout pada periode tertentu
        // tampilkan tanggal checkin dan checkout, nama tamu, kelas kamar nomor kamar, dan status pembayaran
        // Contoh:
        $query = Booking::with(['guest', 'room', 'invoice'])
            ->where('status', 'checkout');

        if ($request->filled('checkin') && $request->filled('checkout')) {
            // Rentang waktu
            $query->whereDate('checkin', '>=', $request->checkin)
                ->whereDate('checkout', '<=', $request->checkout);
        } elseif ($request->filled('checkout')) {
            // Hanya checkout tertentu
            $query->whereDate('checkout', $request->checkout);
        } elseif ($request->filled('checkin')) {
            $query->whereDate('checkin', $request->checkin);
        }

        $bookings = $query->get();

        return view('report.booking.index', compact('bookings'));
        // - Tanggal Checkin: 2023-10-01
        // - Tanggal Checkout: 2023-10-05
        // - Nama Tamu: John Doe
        // - Kelas Kamar: Deluxe
        // - Nomor Kamar: 101
        // - Status Pembayaran: Lunas
        // - Total Bayar: Rp 1.500.000
    }

    // 2. Revenue Report
    public function revenueReport()
    {
        // Logic for generating revenue report
        // Menampilkan total pendapatan contoh :
        // - bookings.total_payment
        // - invoices.created_at
        // - invoices.invoice_number

        // Ambil data booking yang sudah checkout beserta invoice
        $revenues = Booking::with('invoice')
            ->where('status', 'checkout')
            ->get();

        // Hitung total pendapatan
        $totalRevenue = $revenues->sum('total_payment');
        return response()->json([
            'total_revenue' => $totalRevenue,
            'revenues' => $revenues
        ]);

        return view('report.revenue', compact('revenues', 'totalRevenue'));
    }

    // 3. Guest Report
    public function guestReport()
    {
        // Logic for generating guest report
        // Menampilkan data tamu yang pernah menginap contoh :
        // - guests.name
        // - guests.identity_number
        // - bookings.checkin
        // - bookings.checkout

    }

    // 4. Invoice Report
    public function invoiceReport()
    {
        // Logic for generating invoice report
        // Menampilkan data invoice yang pernah dibuat contoh :
        // - invocies.invoice_number
        // - users.username -> melihat siapa yang membuat invoice
        // - invocies.created_at

    }
}
