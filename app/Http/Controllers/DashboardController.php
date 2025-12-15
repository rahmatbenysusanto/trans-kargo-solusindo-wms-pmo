<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        $title = "Dashboard";
        return view('dashboard.index', compact('title'));
    }

    public function stockAvailability(): View
    {
        $title = "Dashboard stockAvailability";
        return view('dashboard.stock', compact('title'));
    }

    public function inboundVsReturn(): View
    {
        $title = "Dashboard Inbound vs Return Trend";
        return view('dashboard.inbound-outbound', compact('title'));
    }

    public function topDevices(): View
    {
        $title = "Dashboard Top Devices by Client";
        return view('dashboard.top-device', compact('title'));
    }
}
