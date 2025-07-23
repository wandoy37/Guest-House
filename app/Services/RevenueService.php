<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class RevenueService
{
    public function getDailyRevenue(Carbon $date): int
    {
        return Booking::with('room')
            ->whereNot('status', 'cancel')
            ->whereDate('checkin', '<=', $date)
            ->whereDate('checkout', '>', $date)
            ->get()
            ->sum(fn($booking) => (int) $booking->room->price);
    }

    public function getWeeklyRevenue($date = null): array
    {
        // Jika ada parameter tanggal, gunakan itu. Kalau tidak, pakai tanggal hari ini.
        $date = $date ? Carbon::parse($date) : Carbon::now();

        // Cari awal minggu dan akhir minggu dari tanggal tersebut.
        $start = $date->copy()->startOfWeek();   // Hari Minggu/Mulai Minggu (tergantung locale)
        $end = $date->copy()->endOfWeek();       // Hari Sabtu/Akhir Minggu

        $data = [];
        // Loop dari awal minggu ke akhir minggu
        for ($d = $start->copy(); $d <= $end; $d->addDay()) {
            $data[] = [
                'tanggal' => $d->toDateString(),
                'pendapatan' => $this->getDailyRevenue($d),
            ];
        }

        // Kembalikan data array
        return [
            'minggu_ini' => ['mulai' => $start->toDateString(), 'sampai' => $end->toDateString()],
            'data' => $data,
        ];
    }

    public function getMonthlyRevenue(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $data = [];
        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $data[] = [
                'tanggal' => $date->toDateString(),
                'pendapatan' => $this->getDailyRevenue($date),
            ];
        }

        return [
            'bulan_ini' => ['mulai' => $start->toDateString(), 'sampai' => $end->toDateString()],
            'data' => $data,
            'total_bulanan' => collect($data)->sum('pendapatan'),
        ];
    }

    public function getYearlyMonthlyRevenue()
    {
        $bookings = Booking::where('status', 'checkin')->get();

        $monthly = collect();

        foreach ($bookings as $booking) {
            $checkin = Carbon::parse($booking->checkin);
            $checkout = Carbon::parse($booking->checkout);
            $price = $booking->room->price;

            for ($date = $checkin->copy(); $date->lt($checkout); $date->addDay()) {
                // Hanya ambil data tahun ini
                if ($date->year === now()->year) {
                    $bulan = $date->format('M'); // Jan, Feb, dst
                    $monthly[$bulan] = ($monthly[$bulan] ?? 0) + $price;
                }
            }
        }

        // Susun sesuai urutan bulan
        $orderedMonths = collect(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
        $result = $orderedMonths->map(function ($bulan) use ($monthly) {
            return [
                'bulan' => $bulan,
                'pendapatan' => $monthly[$bulan] ?? 0,
            ];
        });

        return [
            'tahun' => now()->year,
            'data' => $result,
        ];
    }
}
