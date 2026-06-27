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
        $title = "Dashboard";

        // === KPI Summary Cards ===
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

        // === Stock Availability (Client Distribution) ===
        $stockData = Client::select('client.id', 'client.name')
            ->leftJoin('inventory', 'client.id', '=', 'inventory.client_id')
            ->selectRaw('COALESCE(SUM(CASE WHEN inventory.qty != 0 THEN inventory.qty ELSE 0 END), 0) as total_stock')
            ->groupBy('client.id', 'client.name')
            ->orderByDesc('total_stock')
            ->get();

        $dataClient = $stockData->pluck('name')->toArray();
        $dataStock  = $stockData->pluck('total_stock')->toArray();
        $totalStock = array_sum($dataStock);

        // === Inbound vs Outbound Trend (12 months rolling) ===
        $months    = [];
        $dataMonths   = [];
        $dataInbound  = [];
        $dataOutbound = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'year'  => $date->year,
                'month' => $date->month,
                'label' => $date->format('M Y'),
            ];
        }

        foreach ($months as $month) {
            $year     = $month['year'];
            $monthNum = $month['month'];

            $inbound = DB::table('inbound')
                ->leftJoin('inbound_detail', 'inbound_detail.inbound_id', '=', 'inbound.id')
                ->whereYear('inbound.created_at', $year)
                ->whereMonth('inbound.created_at', $monthNum)
                ->sum('inbound_detail.qty_pa');

            $outboundVal = DB::table('outbound')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('qty');

            $dataMonths[]   = $month['label'];
            $dataInbound[]  = (int) ($inbound ?? 0);
            $dataOutbound[] = (int) ($outboundVal ?? 0);
        }

        // === Lifecycle Status ===
        $lifecycle = DB::table('inventory')
            ->whereNot('qty', 0)
            ->selectRaw("
                SUM(CASE WHEN eos_date IS NULL THEN 1 ELSE 0 END) AS unknown,
                SUM(CASE WHEN eos_date > DATE_ADD(CURDATE(), INTERVAL 6 MONTH) THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN eos_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 6 MONTH) THEN 1 ELSE 0 END) AS near_eos,
                SUM(CASE WHEN eos_date < CURDATE() THEN 1 ELSE 0 END) AS eos
            ")
            ->first();

        $lifecycleChart = [
            (int) $lifecycle->active,
            (int) $lifecycle->near_eos,
            (int) $lifecycle->eos,
            (int) $lifecycle->unknown,
        ];

        // === Top Devices ===
        $topDevices = DB::table('inventory')
            ->whereNot('qty', 0)
            ->select(
                'client_id',
                'part_name',
                'part_number',
                DB::raw('COUNT(serial_number) AS total_unit')
            )
            ->groupBy('client_id', 'part_name', 'part_number')
            ->orderByDesc('total_unit')
            ->limit(8)
            ->get();

        // === Stock Monitoring Summary ===
        $totalItems         = DB::table('inventory')->where('qty', '>', 0)->sum('qty');
        $uniqueParts        = DB::table('inventory')->where('qty', '>', 0)->distinct()->count('part_number');
        $totalSerialNumbers = DB::table('inventory')->where('qty', '>', 0)->whereNotNull('serial_number')->where('serial_number', '!=', '')->count();

        return view('dashboard.index', compact(
            'title',
            'inboundDismantle', 'inboundRelocation', 'outbound', 'return',
            'dataClient', 'dataStock', 'totalStock',
            'dataMonths', 'dataInbound', 'dataOutbound',
            'lifecycle', 'lifecycleChart',
            'topDevices',
            'totalItems', 'uniqueParts', 'totalSerialNumbers'
        ));
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
        $dataStock  = $data->pluck('total_stock')->toArray();
        $stock      = array_sum($dataStock);

        // Additional stats
        $clientsWithStock  = $data->filter(fn($d) => $d->total_stock > 0)->count();
        $avgStockPerClient = $clientsWithStock > 0 ? round($stock / $clientsWithStock) : 0;
        $topClient         = $data->first();
        $stockUtilization  = $stock > 0 ? round(($clientsWithStock / max(1, $data->count())) * 100) : 0;

        // Pie chart data (percentage distribution)
        $pieLabels = $dataClient;
        $pieSeries = $dataStock;

        // Detailed table data with percentages
        $tableData = $data->map(function ($item) use ($stock) {
            $item->percentage = $stock > 0 ? round(($item->total_stock / $stock) * 100, 1) : 0;
            return $item;
        });

        $title = "Dashboard stockAvailability";
        return view('dashboard.stock', compact(
            'title', 'dataStock', 'dataClient', 'stock',
            'clientsWithStock', 'avgStockPerClient', 'topClient', 'stockUtilization',
            'pieLabels', 'pieSeries', 'tableData'
        ));
    }

    public function inboundVsReturn(): View
    {
        $months       = [];
        $dataMonths   = [];
        $dataInbound  = [];
        $dataOutbound = [];
        $tableRows    = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = [
                'year'  => $date->year,
                'month' => $date->month,
                'label' => $date->format('M Y'),
            ];
        }

        $totalInbound  = 0;
        $totalOutbound = 0;
        $peakInbound   = ['month' => '', 'value' => 0];
        $peakOutbound  = ['month' => '', 'value' => 0];

        foreach ($months as $idx => $month) {
            $year     = $month['year'];
            $monthNum = $month['month'];

            $inbound = (int) (DB::table('inbound')
                ->leftJoin('inbound_detail', 'inbound_detail.inbound_id', '=', 'inbound.id')
                ->whereYear('inbound.created_at', $year)
                ->whereMonth('inbound.created_at', $monthNum)
                ->sum('inbound_detail.qty_pa') ?? 0);

            $outboundVal = (int) (DB::table('outbound')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $monthNum)
                ->sum('qty') ?? 0);

            $net = $inbound - $outboundVal;

            $dataMonths[]   = $month['label'];
            $dataInbound[]  = $inbound;
            $dataOutbound[] = $outboundVal;

            $totalInbound  += $inbound;
            $totalOutbound += $outboundVal;

            if ($inbound > $peakInbound['value']) {
                $peakInbound = ['month' => $month['label'], 'value' => $inbound];
            }
            if ($outboundVal > $peakOutbound['value']) {
                $peakOutbound = ['month' => $month['label'], 'value' => $outboundVal];
            }

            $tableRows[] = [
                'month'    => $month['label'],
                'inbound'  => $inbound,
                'outbound' => $outboundVal,
                'net'      => $net,
            ];
        }

        $netFlow = $totalInbound - $totalOutbound;

        $title = "Dashboard Inbound vs Return Trend";
        return view('dashboard.inbound-outbound', compact(
            'title', 'dataMonths', 'dataInbound', 'dataOutbound',
            'totalInbound', 'totalOutbound', 'netFlow',
            'peakInbound', 'peakOutbound', 'tableRows'
        ));
    }

    public function topDevices(Request $request): View
    {
        $clients = Client::all();
        $selectedClient = $request->query('client');

        $inventory = DB::table('inventory')
            ->when($selectedClient, function ($query) use ($selectedClient) {
                $query->where('client_id', $selectedClient);
            })
            ->whereNot('qty', 0)
            ->select(
                'client_id',
                'part_name',
                'part_number',
                DB::raw('COUNT(serial_number) AS total_unit')
            )
            ->groupBy('client_id', 'part_name', 'part_number')
            ->orderByDesc('total_unit')
            ->paginate(10);

        // Chart data: top 12 devices for horizontal bar chart
        $chartDevices = DB::table('inventory')
            ->when($selectedClient, function ($query) use ($selectedClient) {
                $query->where('client_id', $selectedClient);
            })
            ->whereNot('qty', 0)
            ->select(
                'part_name',
                DB::raw('COUNT(serial_number) AS total_unit')
            )
            ->groupBy('part_name')
            ->orderByDesc('total_unit')
            ->limit(12)
            ->get();

        $chartLabels = $chartDevices->pluck('part_name')->toArray();
        $chartSeries = $chartDevices->pluck('total_unit')->toArray();

        // Summary stats
        $totalUniqueParts = DB::table('inventory')
            ->when($selectedClient, function ($query) use ($selectedClient) {
                $query->where('client_id', $selectedClient);
            })
            ->whereNot('qty', 0)
            ->distinct()
            ->count('part_number');

        $totalStockUnits = DB::table('inventory')
            ->when($selectedClient, function ($query) use ($selectedClient) {
                $query->where('client_id', $selectedClient);
            })
            ->whereNot('qty', 0)
            ->sum('qty');

        // Client summary for the selected/all view
        $clientSummary = DB::table('inventory')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->whereNot('inventory.qty', 0)
            ->when($selectedClient, function ($query) use ($selectedClient) {
                $query->where('inventory.client_id', $selectedClient);
            })
            ->select(
                'client.name as client_name',
                DB::raw('COUNT(DISTINCT inventory.part_number) as part_count'),
                DB::raw('SUM(inventory.qty) as total_qty')
            )
            ->groupBy('client.id', 'client.name')
            ->orderByDesc('total_qty')
            ->get();

        $title = "Dashboard Top Devices by Client";
        return view('dashboard.top-device', compact(
            'title', 'clients', 'inventory', 'selectedClient',
            'chartLabels', 'chartSeries', 'totalUniqueParts', 'totalStockUnits',
            'clientSummary'
        ));
    }

    public function lifecycleStatusDistributor(): View
    {
        $data = DB::table('inventory')
            ->whereNot('qty', 0)
            ->selectRaw("
                SUM(CASE WHEN eos_date IS NULL THEN 1 ELSE 0 END) AS unknown,
                SUM(CASE WHEN eos_date > DATE_ADD(CURDATE(), INTERVAL 6 MONTH) THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN eos_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 6 MONTH) THEN 1 ELSE 0 END) AS near_eos,
                SUM(CASE WHEN eos_date < CURDATE() THEN 1 ELSE 0 END) AS eos
            ")
            ->first();

        $chart = [(int)$data->active, (int)$data->near_eos, (int)$data->eos, (int)$data->unknown];
        $totalDevices = array_sum($chart);

        // Percentages
        $pctActive  = $totalDevices > 0 ? round(($data->active / $totalDevices) * 100, 1) : 0;
        $pctNearEos = $totalDevices > 0 ? round(($data->near_eos / $totalDevices) * 100, 1) : 0;
        $pctEos     = $totalDevices > 0 ? round(($data->eos / $totalDevices) * 100, 1) : 0;
        $pctUnknown = $totalDevices > 0 ? round(($data->unknown / $totalDevices) * 100, 1) : 0;

        // Devices approaching EOS (near_eos) with details
        $nearEosDevices = DB::table('inventory')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->leftJoin('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->where('inventory.qty', '>', 0)
            ->whereNotNull('inventory.eos_date')
            ->whereBetween('inventory.eos_date', [Carbon::now(), Carbon::now()->addMonths(6)])
            ->select(
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'inventory.eos_date',
                'client.name as client_name',
                'storage_bin.name as bin_name'
            )
            ->orderBy('inventory.eos_date')
            ->limit(10)
            ->get();

        // Already EOS devices (critical)
        $eosDevices = DB::table('inventory')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->where('inventory.qty', '>', 0)
            ->whereNotNull('inventory.eos_date')
            ->where('inventory.eos_date', '<', Carbon::now())
            ->select(
                'inventory.part_name',
                'inventory.part_number',
                'inventory.serial_number',
                'inventory.eos_date',
                'client.name as client_name'
            )
            ->orderBy('inventory.eos_date', 'desc')
            ->limit(10)
            ->get();

        $title = "Lifecycle Status Distributor";
        return view('dashboard.lifecycle-status-distributor', compact(
            'title', 'data', 'chart', 'totalDevices',
            'pctActive', 'pctNearEos', 'pctEos', 'pctUnknown',
            'nearEosDevices', 'eosDevices'
        ));
    }
    public function stockMonitoring(Request $request): View
    {
        $stocks = DB::table('inventory')
            ->select('part_name', 'part_number', DB::raw('SUM(qty) as total_qty'))
            ->where('qty', '>', 0)
            ->groupBy('part_name', 'part_number')
            ->orderByDesc('total_qty')
            ->get();

        $totalItems         = DB::table('inventory')->where('qty', '>', 0)->sum('qty');
        $uniqueParts        = $stocks->count();
        $totalSerialNumbers = DB::table('inventory')->where('qty', '>', 0)->whereNotNull('serial_number')->where('serial_number', '!=', '')->count();

        // Condition breakdown
        $conditionStats = DB::table('inventory')
            ->where('qty', '>', 0)
            ->select('condition', DB::raw('SUM(qty) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('condition')
            ->get();

        // Status breakdown
        $statusStats = DB::table('inventory')
            ->where('qty', '>', 0)
            ->select('status', DB::raw('SUM(qty) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Low stock items (qty <= 5)
        $lowStockItems = DB::table('inventory')
            ->where('qty', '>', 0)
            ->where('qty', '<=', 5)
            ->select('part_name', 'part_number', 'serial_number', 'qty', 'condition', 'status')
            ->orderBy('qty')
            ->limit(10)
            ->get();

        $lowStockCount = DB::table('inventory')->where('qty', '>', 0)->where('qty', '<=', 5)->count();

        // Top 10 parts for chart
        $topPartsChart = $stocks->take(10);

        // Client-level stock breakdown
        $clientStock = DB::table('inventory')
            ->leftJoin('client', 'inventory.client_id', '=', 'client.id')
            ->where('inventory.qty', '>', 0)
            ->select('client.name as client_name', DB::raw('SUM(inventory.qty) as total_qty'))
            ->groupBy('client.id', 'client.name')
            ->orderByDesc('total_qty')
            ->get();

        $title = "Stock Monitoring";
        return view('dashboard.stock_monitoring', compact(
            'title', 'stocks', 'totalItems', 'uniqueParts', 'totalSerialNumbers',
            'conditionStats', 'statusStats', 'lowStockItems', 'lowStockCount',
            'topPartsChart', 'clientStock'
        ));
    }

    public function stockMonitoringDetail(Request $request)
    {
        $partName = $request->query('part_name');
        $partNumber = $request->query('part_number');

        $serials = DB::table('inventory')
            ->leftJoin('storage_bin', 'inventory.bin_id', '=', 'storage_bin.id')
            ->where('part_name', $partName)
            ->where('part_number', $partNumber)
            ->where('qty', '>', 0)
            ->select(
                'serial_number',
                'storage_bin.name as bin_name',
                'inventory.status',
                'inventory.condition',
                'inventory.pic'
            )
            ->get();

        return response()->json($serials);
    }
}
