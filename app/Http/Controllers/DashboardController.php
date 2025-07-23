<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\RevenueService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }
    public function index(RevenueService $revenueService)
    {
        $todaysRevenue = $revenueService->getDailyRevenue(now());
        $weekly = $revenueService->getWeeklyRevenue();

        // Ambil data mingguan
        $weeklyLabels = collect($weekly['data'])->pluck('tanggal')->toArray();
        $weeklyValues = collect($weekly['data'])->pluck('pendapatan')->toArray();

        // buat logic untuk mencari data booking yang status checkin hari ini
        $checkIns = Booking::where('status', 'checkin')->get();

        // buat logic untuk mencari data booking yang checkout hari ini
        $toDays = now()->toDateString(); // Hasil: "2025-07-23"
        $checkOuts = Booking::where('checkout', $toDays)->get();

        // buat logic untuk mencari data room yang tersedia
        $available = Room::where('status', 'available')->get();

        return view('dashboard.index', compact(
            'todaysRevenue',
            'weeklyLabels',
            'weeklyValues',
            'checkIns',
            'checkOuts',
            'available',
        ));
    }
}
