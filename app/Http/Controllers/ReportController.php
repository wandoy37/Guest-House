<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\RevenueService;

class ReportController extends Controller
{
    protected $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    // 1. Booking Report
    public function bookingReport(Request $request)
    {
        // Ambil parameter period_start dan period_end dari form
        $periodStart = $request->input('period_start');
        $periodEnd = $request->input('period_end');

        // Mulai query booking yang status checkout
        $query = Booking::whereNot('status', 'cancel');

        // Jika user tidak mengisi period_start & period_end → default bulan ini
        if (!$periodStart && !$periodEnd) {
            $query->whereMonth('checkout', now()->month)
                ->whereYear('checkout', now()->year);
        }

        // Jika ada periode, tambahkan filter
        if ($periodStart) {
            $query->whereDate('checkout', '>=', $periodStart);
        }
        if ($periodEnd) {
            $query->whereDate('checkout', '<=', $periodEnd);
        }

        // Urutkan dari checkout terbaru
        $bookings = $query->orderBy('checkout', 'desc')->get();

        // Kirim data ke blade
        return view('report.booking.index', compact('bookings', 'periodStart', 'periodEnd'));
    }

    public function bookingReportShow($id)
    {
        $booking = Booking::with(['guest', 'room', 'logs.user'])
            ->where('id', $id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        return view('report.booking.show', compact('booking'));
    }

    // 2. Revenue Report
    public function revenueReport(RevenueService $revenueService)
    {
        // Ambil data harian bulan ini
        $monthly = $revenueService->getMonthlyRevenue();
        // return response()->json($monthly);

        // Kelompokkan data ke minggu ke-n dalam bulan
        $weeklyRevenue = collect($monthly['data'])
            ->groupBy(function ($item) {
                return Carbon::parse($item['tanggal'])->weekOfMonth;
            })
            ->map(function ($items) {
                return $items->sum('pendapatan');
            });

        // Ambil minggu yang tersedia secara otomatis
        $maxWeek = $weeklyRevenue->keys()->max();
        $weeklyLabels = [];
        $weeklyValues = [];
        for ($i = 1; $i <= $maxWeek; $i++) {
            $weeklyLabels[] = 'Week ' . $i;
            $weeklyValues[] = $weeklyRevenue->get($i, 0); // default 0 jika minggu kosong
        }

        return view('report.revenue', compact('weeklyLabels', 'weeklyValues', 'monthly'));
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

        $guests = \App\Models\Booking::with('guest')
            ->where('status', 'checkout')
            ->orderBy('checkout', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'name' => $booking->guest->name ?? '-',
                    'identity_number' => $booking->guest->identity_number ?? '-',
                    'checkin' => $booking->checkin,
                    'checkout' => $booking->checkout,
                ];
            });

        return view('report.guest.index', compact('guests'));
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
