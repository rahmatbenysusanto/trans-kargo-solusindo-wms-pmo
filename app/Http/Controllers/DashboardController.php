<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Inventory;
use App\Models\Outbound;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        $inboundDismantle = Inbound::where('inbound_type', 'Dismantle')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('quantity');

        $inboundRelocation = Inbound::where('inbound_type', 'Relocation')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('quantity');

        $outbound = Outbound::where('type', 'outbound')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty');

        $return = Outbound::where('type', 'return')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty');

        $title = "Dashboard";
        return view('dashboard.index', compact('title', 'inboundDismantle', 'inboundRelocation', 'outbound', 'return'));
    }

    public function stockAvailability(): View
    {
        $data = Client::select('client.id', 'client.name')
            ->leftJoin('inventory', 'client.id', '=', 'inventory.client_id')
            ->selectRaw('COALESCE(SUM(CASE WHEN inventory.qty != 0 THEN inventory.qty ELSE 0 END), 0) as total_stock')
            ->groupBy('client.id', 'client.name')
            ->orderByDesc('total_stock')
            ->get();

        $dataClient = $data->pluck('name')->toArray();
        $dataStock = $data->pluck('total_stock')->toArray();
        $stock = array_sum($dataStock);

        $title = "Dashboard stockAvailability";
        return view('dashboard.stock', compact('title', 'dataStock', 'dataClient', 'stock'));
    }

    public function inboundVsReturn(): View
    {
        $months = [];
        $dataMonths = [];
        $dataInbound = [];
        $dataOutbound = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'year' => $date->year,
                'month' => $date->month,
                'label' => $date->format('M Y')
            ];
        }

        foreach ($months as $month) {
            $year = $month['year'];
            $monthNum = $month['month'];

            // Hitung inbound (masuk)
            $inbound = DB::table('inbound')
                ->leftJoin('inbound_detail', 'inbound_detail.inbound_id', '=', 'inbound.id')
                ->whereYear('inbound.created_at', $year)
                ->whereMonth('inbound.created_at', $monthNum)
                ->sum('inbound_detail.qty_pa');

            // Hitung outbound (keluar)
            $outbound = DB::table('outbound')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('qty');

            $dataMonths[] = $month['label'];
            $dataInbound[] = $inbound ?? 0;
            $dataOutbound[] = $outbound ?? 0;
        }

        $title = "Dashboard Inbound vs Return Trend";
        return view('dashboard.inbound-outbound', compact('title', 'dataMonths', 'dataInbound', 'dataOutbound'));
    }

    public function topDevices(Request $request): View
    {
        $client = Client::all();

        $inventory = DB::table('inventory')
            ->when($request->query('client'), function ($query) use ($request) {
                $query->where('client_id', $request->query('client'));
            })
            ->whereNot('qty', 0)
            ->select(
                'client_id',
                'part_name',
                'part_number',
                DB::raw('COUNT(serial_number) AS total_unit')
            )
            ->groupBy(
                'client_id',
                'part_name',
                'part_number'
            )
            ->orderByDesc('total_unit')
            ->paginate(10);

        $title = "Dashboard Top Devices by Client";
        return view('dashboard.top-device', compact('title', 'client', 'inventory'));
    }

    public function lifecycleStatusDistributor(): View
    {
        $data = DB::table('inventory')
            ->whereNot('qty', 0)
            ->selectRaw("
                SUM(CASE WHEN eos_date IS NULL THEN 1 ELSE 0 END) AS unknown,
                SUM(CASE
                    WHEN eos_date > DATE_ADD(CURDATE(), INTERVAL 6 MONTH)
                    THEN 1 ELSE 0
                END) AS active,
                SUM(CASE
                    WHEN eos_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 6 MONTH)
                    THEN 1 ELSE 0
                END) AS near_eos,
                SUM(CASE
                    WHEN eos_date < CURDATE()
                    THEN 1 ELSE 0
                END) AS eos
            ")
            ->first();

        $chart = [(int)$data->active, (int)$data->near_eos, (int)$data->eos, (int)$data->unknown];

        $title = "Lifecycle Status Distributor";
        return view('dashboard.lifecycle-status-distributor', compact('title', 'data', 'chart'));
    }
}
