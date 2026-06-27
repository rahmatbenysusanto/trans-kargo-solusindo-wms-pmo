@extends('layout.index')
@section('title', 'Stock Availability')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-gradient-primary">Stock Availability</h4>
                <p class="text-muted mb-0 fs-13">Client-level inventory distribution, utilization & health analysis</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-soft-primary text-primary fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-building-2-line me-1"></i> {{ $clientsWithStock }}/{{ count($dataClient) }} Active Clients
                </span>
                <span class="badge bg-soft-success text-success fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-check-double-line me-1"></i> Live
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ===== KPI SUMMARY ROW ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-green">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-success"><i class="mdi mdi-package-variant-closed"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Total Available Stock</span>
                    <h2 class="kpi-value">{{ number_format($stock) }}</h2>
                    <span class="kpi-badge text-success">All Clients Combined</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-blue">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-info"><i class="mdi mdi-account-group-outline"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Active Clients</span>
                    <h2 class="kpi-value">{{ $clientsWithStock }} <small class="fs-16 text-muted">/ {{ count($dataClient) }}</small></h2>
                    <span class="kpi-badge text-info">{{ $stockUtilization }}% Utilization Rate</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-purple">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-primary"><i class="mdi mdi-calculator-variant-outline"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Avg Stock / Client</span>
                    <h2 class="kpi-value">{{ number_format($avgStockPerClient) }}</h2>
                    <span class="kpi-badge text-primary">Per Active Client</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-orange">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-warning"><i class="mdi mdi-trophy-outline"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Top Client</span>
                    <h2 class="kpi-value fs-20">{{ $topClient ? \Illuminate\Support\Str::limit($topClient->name, 14) : '-' }}</h2>
                    <span class="kpi-badge text-warning">{{ $topClient ? number_format($topClient->total_stock) . ' Units' : 'N/A' }}</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>
</div>

{{-- ===== CHARTS ROW ===== --}}
<div class="row g-4 mb-4">
    {{-- Horizontal Bar Chart --}}
    <div class="col-xl-7 col-lg-7">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-bar me-2 text-success"></i>Stock Distribution by Client</h5>
                    <p class="text-muted small mb-0 mt-1">Horizontal breakdown — higher bars = more inventory</p>
                </div>
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                    <i class="mdi mdi-chart-pie me-1"></i> Bar Chart
                </span>
            </div>
            <div class="card-body p-4">
                <div id="bar_chart" data-colors='["--vz-success"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    {{-- Donut Chart --}}
    <div class="col-xl-5 col-lg-5">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-donut me-2 text-primary"></i>Share of Stock</h5>
                    <p class="text-muted small mb-0 mt-1">Percentage distribution across clients</p>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="pie_chart" data-colors='["#28a745","#20c997","#17a2b8","#6f42c1","#fd7e14","#e83e8c","#405189","#f1c40f","#e74c3c","#2ecc71","#3498db","#9b59b6"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== DETAIL TABLE ===== --}}
<div class="row">
    <div class="col-12">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-table-large me-2 text-info"></i>Detailed Stock Allocation</h5>
                    <p class="text-muted small mb-0 mt-1">Complete breakdown per client with percentage & progress visualization</p>
                </div>
                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                    {{ $tableData->count() }} Clients Listed
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width:60px">#</th>
                                <th>Client Name</th>
                                <th class="text-center" style="width:130px">Stock Units</th>
                                <th class="text-center" style="width:100px">Share %</th>
                                <th style="width:200px">Distribution</th>
                                <th class="text-center" style="width:90px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tableData as $index => $item)
                            @php
                                $pct = $item->percentage;
                                if ($pct >= 30) $statusCls = 'bg-success-subtle text-success';
                                elseif ($pct >= 10) $statusCls = 'bg-primary-subtle text-primary';
                                elseif ($pct > 0) $statusCls = 'bg-warning-subtle text-warning';
                                else $statusCls = 'bg-light text-muted';
                                $statusLabel = $pct >= 30 ? 'Major' : ($pct >= 10 ? 'Medium' : ($pct > 0 ? 'Minor' : 'Empty'));
                            @endphp
                            <tr class="table-row-hover">
                                <td class="ps-4">
                                    @if($index == 0) <span class="rank-badge gold">🥇</span>
                                    @elseif($index == 1) <span class="rank-badge silver">🥈</span>
                                    @elseif($index == 2) <span class="rank-badge bronze">🥉</span>
                                    @else <span class="rank-number text-muted fw-semibold">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="device-avatar me-3">
                                            <span class="device-avatar-text bg-soft-primary text-primary fw-bold">
                                                {{ strtoupper(substr($item->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $item->name }}</h6>
                                            <small class="text-muted fs-11">Client ID: {{ $item->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold fs-15">{{ number_format($item->total_stock) }}</td>
                                <td class="text-center fw-semibold">{{ $pct }}%</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:8px; border-radius:10px;">
                                            <div class="progress-bar bg-{{ $pct >= 30 ? 'success' : ($pct >= 10 ? 'primary' : 'warning') }} rounded-pill"
                                                 style="width:{{ max(2, $pct) }}%"></div>
                                        </div>
                                        <small class="text-muted" style="min-width:40px">{{ $pct }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-2 {{ $statusCls }}">{{ $statusLabel }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No stock data available.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light-subtle">
                            <tr>
                                <td colspan="2" class="ps-4 fw-bold">TOTAL</td>
                                <td class="text-center fw-bold fs-15">{{ number_format($stock) }}</td>
                                <td class="text-center fw-bold">100%</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function getChartColorsArray(t) {
    if (null !== document.getElementById(t)) {
        return t = document.getElementById(t).getAttribute("data-colors"),
            (t = JSON.parse(t)).map(function(t) {
                var e = t.replace(" ", "");
                return -1 === e.indexOf(",") ?
                    getComputedStyle(document.documentElement).getPropertyValue(e) || e :
                    2 === (t = t.split(",")).length ?
                    "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(t[0]) + "," + t[1] + ")" :
                    e
            })
    }
}

// Bar Chart
var barColors = getChartColorsArray("bar_chart");
if (barColors) {
    new ApexCharts(document.querySelector("#bar_chart"), {
        chart: { height: 400, type: "bar", toolbar: { show: false }, animations: { enabled: true, easing: 'easeinout', speed: 800 } },
        plotOptions: { bar: { horizontal: true, borderRadius: 8, borderRadiusApplication: 'end', barHeight: '50%', distributed: true } },
        dataLabels: { enabled: true, textAnchor: 'start', offsetX: 8, style: { colors: ['#fff'], fontWeight: 'bold', fontSize: '12px' }, formatter: function(v) { return v + ' Units'; }, dropShadow: { enabled: true } },
        series: [{ name: "Stock", data: {!! json_encode($dataStock) !!} }],
        colors: barColors,
        grid: { borderColor: "#f0f0f0", strokeDashArray: 4, padding: { left: 20, right: 50, top: 0, bottom: 0 } },
        xaxis: { categories: {!! json_encode($dataClient) !!}, labels: { style: { colors: '#74788d', fontWeight: 500 } } },
        yaxis: { labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '13px' } } },
        tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' Units'; } } }
    }).render();
}

// Donut Chart
var pieColors = getChartColorsArray("pie_chart");
if (pieColors) {
    new ApexCharts(document.querySelector("#pie_chart"), {
        series: {!! json_encode($pieSeries) !!},
        chart: { type: 'donut', height: 380, toolbar: { show: false } },
        labels: {!! json_encode($pieLabels) !!},
        colors: pieColors,
        stroke: { width: 0 },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, name: { show: true, fontSize: '13px', fontWeight: 600 }, value: { show: true, fontSize: '18px', fontWeight: 'bold' }, total: { show: true, label: 'Total Stock', fontSize: '14px', fontWeight: 600, color: '#74788d', formatter: function(w) { return w.globals.seriesTotals.reduce(function(a,b){return a+b;},0) + ' Units'; } } } } } },
        legend: { position: 'bottom', fontSize: '12px', fontWeight: 500, itemMargin: { horizontal: 5, vertical: 5 }, markers: { width: 10, height: 10, radius: 4 } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' Units'; } } }
    }).render();
}
</script>
@endsection
