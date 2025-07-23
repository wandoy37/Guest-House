<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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

        return view('dashboard.index', compact(
            'todaysRevenue',
            'weeklyLabels',
            'weeklyValues',
        ));
    }
}
