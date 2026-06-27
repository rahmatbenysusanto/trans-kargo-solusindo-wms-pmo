@extends('layout.index')
@section('title', 'Inbound vs Return Trend')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-gradient-primary">Transaction Trends</h4>
                <p class="text-muted mb-0 fs-13">12-month rolling inbound vs outbound with KPI summary</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-soft-primary text-primary fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-calendar-line me-1"></i> Last 12 Months
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
        <div class="kpi-card kpi-card-purple">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-primary"><i class="mdi mdi-package-down"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Total Inbound (12M)</span>
                    <h2 class="kpi-value">{{ number_format($totalInbound) }}</h2>
                    <span class="kpi-badge text-primary">Items Received</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-green">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-success"><i class="mdi mdi-truck-fast-outline"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Total Outbound (12M)</span>
                    <h2 class="kpi-value">{{ number_format($totalOutbound) }}</h2>
                    <span class="kpi-badge text-success">Items Shipped</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card @if($netFlow >= 0) kpi-card-blue @else" style="border-top:3px solid #e74c3c" @endif>
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon @if($netFlow >= 0) bg-info @else bg-danger @endif">
                        <i class="mdi mdi-arrow-decision-outline"></i>
                    </div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Net Flow (12M)</span>
                    <h2 class="kpi-value @if($netFlow < 0) text-danger @endif">{{ $netFlow >= 0 ? '+' : '' }}{{ number_format($netFlow) }}</h2>
                    <span class="kpi-badge @if($netFlow >= 0) text-info @else text-danger @endif">
                        {{ $netFlow >= 0 ? 'Surplus (More In)' : 'Deficit (More Out)' }}
                    </span>
                </div>
            </div>
            <div class="kpi-glow" @if($netFlow < 0) style="background:linear-gradient(90deg,#e74c3c,#f07167)" @endif></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-orange">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon bg-warning"><i class="mdi mdi-trending-up"></i></div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label">Peak Inbound Month</span>
                    <h2 class="kpi-value fs-20">{{ $peakInbound['month'] }}</h2>
                    <span class="kpi-badge text-warning">{{ number_format($peakInbound['value']) }} Units</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>
</div>

{{-- ===== MAIN CHART (Full Width) ===== --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-trending-up me-2 text-primary"></i>Inbound vs Outbound — 12 Month Trend</h5>
                    <p class="text-muted small mb-0 mt-1">Bar = Items Received | Area = Items Shipped | Line = Trend</p>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="d-flex align-items-center gap-1"><span class="legend-dot bg-primary"></span><span class="fs-13 text-muted">Received</span></div>
                    <div class="d-flex align-items-center gap-1"><span class="legend-dot bg-success"></span><span class="fs-13 text-muted">Shipped</span></div>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="trend_chart" data-colors='["#405189","#28a745"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MONTHLY TABLE ===== --}}
<div class="row g-4">
    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-table-large me-2 text-info"></i>Monthly Breakdown</h5>
            </div>
            <div class="card-body p-0" style="max-height:480px; overflow-y:auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th class="ps-4">Month</th>
                            <th class="text-center">Inbound</th>
                            <th class="text-center">Outbound</th>
                            <th class="text-center">Net</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableRows as $row)
                        <tr class="table-row-hover">
                            <td class="ps-4 fw-semibold">{{ $row['month'] }}</td>
                            <td class="text-center">{{ number_format($row['inbound']) }}</td>
                            <td class="text-center">{{ number_format($row['outbound']) }}</td>
                            <td class="text-center fw-bold {{ $row['net'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $row['net'] >= 0 ? '+' : '' }}{{ number_format($row['net']) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light-subtle fw-bold">
                        <tr>
                            <td class="ps-4">TOTAL</td>
                            <td class="text-center">{{ number_format($totalInbound) }}</td>
                            <td class="text-center">{{ number_format($totalOutbound) }}</td>
                            <td class="text-center {{ $netFlow >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $netFlow >= 0 ? '+' : '' }}{{ number_format($netFlow) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-bar me-2 text-success"></i>Inbound vs Outbound Comparison</h5>
            </div>
            <div class="card-body p-4">
                <div id="compare_chart" data-colors='["#405189","#28a745"]' class="apex-charts" dir="ltr"></div>
                <div class="mt-4">
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <span class="text-muted fs-13">Peak Outbound</span>
                            <h5 class="fw-bold text-primary mt-1">{{ $peakOutbound['month'] }}</h5>
                            <span class="badge bg-primary-subtle text-primary">{{ number_format($peakOutbound['value']) }} units</span>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <span class="text-muted fs-13">Avg Monthly Inbound</span>
                            <h5 class="fw-bold text-success mt-1">{{ number_format(round($totalInbound / 12)) }}</h5>
                            <span class="badge bg-success-subtle text-success">per month</span>
                        </div>
                        <div class="vr"></div>
                        <div>
                            <span class="text-muted fs-13">Avg Monthly Outbound</span>
                            <h5 class="fw-bold text-info mt-1">{{ number_format(round($totalOutbound / 12)) }}</h5>
                            <span class="badge bg-info-subtle text-info">per month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function getChartColorsArray(e) {
    if (null !== document.getElementById(e)) {
        var t = document.getElementById(e).getAttribute("data-colors");
        if (t) return (t = JSON.parse(t)).map(function(e) {
            var t = e.replace(" ", "");
            return -1 === t.indexOf(",") ? getComputedStyle(document.documentElement).getPropertyValue(t) || t :
                2 == (e = e.split(",")).length ? "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(e[0]) + "," + e[1] + ")" : t
        });
    }
}

// Main Trend Chart
var trendColors = getChartColorsArray("trend_chart");
if (trendColors) {
    new ApexCharts(document.querySelector("#trend_chart"), {
        series: [
            { name: "Items Received", type: "bar", data: {!! json_encode($dataInbound) !!} },
            { name: "Items Shipped", type: "area", data: {!! json_encode($dataOutbound) !!} }
        ],
        chart: { height: 420, type: "line", toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } }, animations: { enabled: true, easing: 'easeinout', speed: 1000 }, dropShadow: { enabled: true, color: '#000', top: 15, left: 5, blur: 8, opacity: 0.06 } },
        stroke: { curve: "smooth", width: [0, 3] },
        fill: { type: ['solid', 'gradient'], gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
        markers: { size: [0, 5], strokeWidth: 2, hover: { size: 7 } },
        xaxis: { categories: {!! json_encode($dataMonths) !!}, axisTicks: { show: false }, axisBorder: { show: false }, labels: { style: { colors: '#74788d', fontWeight: 500 } } },
        yaxis: { title: { text: 'Quantity', style: { color: '#adb5bd', fontWeight: 500 } }, labels: { style: { colors: '#74788d' } } },
        grid: { borderColor: '#f0f0f0', strokeDashArray: 4, padding: { top: 0, right: 30, bottom: 0, left: 10 } },
        legend: { show: false },
        plotOptions: { bar: { columnWidth: "28%", borderRadius: 5, borderRadiusApplication: 'end' } },
        colors: trendColors,
        tooltip: { shared: true, intersect: false, theme: 'dark' }
    }).render();
}

// Comparison Bar Chart
var compareColors = getChartColorsArray("compare_chart");
if (compareColors) {
    new ApexCharts(document.querySelector("#compare_chart"), {
        series: [
            { name: "Inbound", data: {!! json_encode($dataInbound) !!} },
            { name: "Outbound", data: {!! json_encode($dataOutbound) !!} }
        ],
        chart: { type: 'bar', height: 320, toolbar: { show: false }, animations: { enabled: true, speed: 800 } },
        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 6, borderRadiusApplication: 'end' } },
        dataLabels: { enabled: false },
        xaxis: { categories: {!! json_encode($dataMonths) !!}, labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '11px' }, rotate: -45 } },
        yaxis: { labels: { style: { colors: '#74788d' } } },
        grid: { borderColor: '#f0f0f0', strokeDashArray: 4 },
        colors: compareColors,
        legend: { position: 'top', fontSize: '12px', fontWeight: 500 },
        tooltip: { theme: 'dark' }
    }).render();
}
</script>
@endsection
