@extends('layout.index')
@section('title', 'Lifecycle Status')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-gradient-primary">Asset Lifecycle Health</h4>
                <p class="text-muted mb-0 fs-13">End-of-Support monitoring, alerts & detailed device tracking</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-soft-primary text-primary fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-device-line me-1"></i> {{ number_format($totalDevices) }} Total Devices
                </span>
                <span class="badge bg-soft-success text-success fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-check-double-line me-1"></i> Live
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ===== KPI CARDS ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-green">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-success"><i class="mdi mdi-shield-check-outline"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label">Active (Support OK)</span>
                    <h2 class="kpi-value">{{ number_format($data->active) }} <small class="fs-16 text-muted">/ {{ $totalDevices }}</small></h2>
                    <span class="kpi-badge text-success">{{ $pctActive }}% — Support > 6 months</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-orange">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-warning"><i class="mdi mdi-alert-circle-outline"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label">⚠ Near EOS</span>
                    <h2 class="kpi-value">{{ number_format($data->near_eos) }} <small class="fs-16 text-muted">/ {{ $totalDevices }}</small></h2>
                    <span class="kpi-badge text-warning">{{ $pctNearEos }}% — Requires Planning ≤ 6m</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card" style="border-top:3px solid #e74c3c;">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-danger"><i class="mdi mdi-close-octagon-outline"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label" style="color:#e74c3c;">🔴 EOL / EOS Reached</span>
                    <h2 class="kpi-value">{{ number_format($data->eos) }} <small class="fs-16 text-muted">/ {{ $totalDevices }}</small></h2>
                    <span class="kpi-badge text-danger">{{ $pctEos }}% — Critical Replacement</span>
                </div>
            </div>
            <div class="kpi-glow" style="background:linear-gradient(90deg,#e74c3c,#f07167);"></div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card" style="border-top:3px solid #95a5a6;">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-secondary"><i class="mdi mdi-help-circle-outline"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label" style="color:#95a5a6;">Unknown</span>
                    <h2 class="kpi-value">{{ number_format($data->unknown) }} <small class="fs-16 text-muted">/ {{ $totalDevices }}</small></h2>
                    <span class="kpi-badge text-secondary">{{ $pctUnknown }}% — Missing EOS Date</span>
                </div>
            </div>
            <div class="kpi-glow" style="background:linear-gradient(90deg,#95a5a6,#bdc3c7);"></div>
        </div>
    </div>
</div>

{{-- ===== CHARTS ROW ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-5 col-lg-5">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-donut me-2 text-primary"></i>Lifecycle Distribution</h5>
            </div>
            <div class="card-body p-4">
                <div id="donut_chart" data-colors='["#28a745","#f1c40f","#e74c3c","#95a5a6"]' class="apex-charts" dir="ltr"></div>
                <div class="d-flex justify-content-around text-center mt-2">
                    <div><span class="badge bg-success rounded-pill px-3">Active {{ $pctActive }}%</span></div>
                    <div><span class="badge bg-warning rounded-pill px-3">Near {{ $pctNearEos }}%</span></div>
                    <div><span class="badge bg-danger rounded-pill px-3">EOS {{ $pctEos }}%</span></div>
                    <div><span class="badge bg-secondary rounded-pill px-3">? {{ $pctUnknown }}%</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7 col-lg-7">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-bubble me-2 text-info"></i>Lifecycle Health Overview</h5>
            </div>
            <div class="card-body p-4">
                <div id="radial_chart" data-colors='["#28a745","#f1c40f","#e74c3c","#95a5a6"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== NEAR EOS & EOS TABLES ===== --}}
<div class="row g-4">
    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-alert-outline me-2 text-warning"></i>⚠ Devices Approaching EOS (≤ 6 months)</h5>
                    <p class="text-muted small mb-0 mt-1">Require immediate planning & replacement scheduling</p>
                </div>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">{{ $nearEosDevices->count() }} Devices</span>
            </div>
            <div class="card-body p-0" style="max-height:380px; overflow-y:auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th class="ps-4">Part Name</th>
                            <th>Serial Number</th>
                            <th>EOS Date</th>
                            <th class="text-center">Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nearEosDevices as $device)
                        @php
                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($device->eos_date), false);
                        @endphp
                        <tr class="table-row-hover">
                            <td class="ps-4">
                                <div>
                                    <span class="fw-semibold fs-14">{{ $device->part_name }}</span>
                                    <br><small class="text-muted">{{ $device->client_name ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td><code class="part-number-code">{{ $device->serial_number ?? '-' }}</code></td>
                            <td><span class="fw-medium">{{ \Carbon\Carbon::parse($device->eos_date)->format('d M Y') }}</span></td>
                            <td class="text-center">
                                <span class="badge rounded-pill px-2 {{ $daysLeft <= 30 ? 'bg-danger-subtle text-danger' : ($daysLeft <= 90 ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info') }}">
                                    {{ $daysLeft }} days
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">🎉 No devices approaching EOS.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-skull-outline me-2 text-danger"></i>🔴 Devices Already EOS (Critical)</h5>
                    <p class="text-muted small mb-0 mt-1">Support expired — urgent replacement needed</p>
                </div>
                <span class="badge bg-danger px-3 py-2 rounded-pill">{{ $eosDevices->count() }} Devices</span>
            </div>
            <div class="card-body p-0" style="max-height:380px; overflow-y:auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th class="ps-4">Part Name</th>
                            <th>Serial Number</th>
                            <th>EOS Date</th>
                            <th class="text-center">Overdue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eosDevices as $device)
                        @php
                            $daysOver = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($device->eos_date), false);
                        @endphp
                        <tr class="table-row-hover">
                            <td class="ps-4">
                                <div>
                                    <span class="fw-semibold fs-14">{{ $device->part_name }}</span>
                                    <br><small class="text-muted">{{ $device->client_name ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td><code class="part-number-code">{{ $device->serial_number ?? '-' }}</code></td>
                            <td><span class="fw-medium text-danger">{{ \Carbon\Carbon::parse($device->eos_date)->format('d M Y') }}</span></td>
                            <td class="text-center">
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2">
                                    {{ abs($daysOver) }} days
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">✅ No overdue EOS devices.</td></tr>
                        @endforelse
                    </tbody>
                </table>
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

// Donut Chart
var donutColors = getChartColorsArray("donut_chart");
if (donutColors) {
    new ApexCharts(document.querySelector("#donut_chart"), {
        series: {!! json_encode($chart) !!},
        chart: { type: 'donut', height: 320, toolbar: { show: false } },
        labels: ['Active', 'Near EOS', 'EOS Reached', 'Unknown'],
        colors: donutColors,
        stroke: { width: 0 },
        plotOptions: { pie: { donut: { size: '68%', labels: { show: true, name: { show: true, fontSize: '13px', fontWeight: 600 }, value: { show: true, fontSize: '18px', fontWeight: 'bold' }, total: { show: true, label: 'Total Devices', fontSize: '14px', fontWeight: 600, color: '#74788d', formatter: function(w) { return w.globals.seriesTotals.reduce(function(a,b){return a+b;},0); } } } } } },
        legend: { position: 'bottom', fontSize: '12px', fontWeight: 500, markers: { width: 10, height: 10, radius: 4 } },
        dataLabels: { enabled: false },
        tooltip: { theme: 'dark' }
    }).render();
}

// Radial Bar Chart
var radialColors = getChartColorsArray("radial_chart");
if (radialColors) {
    new ApexCharts(document.querySelector("#radial_chart"), {
        series: [{!! json_encode($pctActive) !!}, {!! json_encode($pctNearEos) !!}, {!! json_encode($pctEos) !!}, {!! json_encode($pctUnknown) !!}],
        chart: { type: 'radialBar', height: 340, toolbar: { show: false } },
        plotOptions: {
            radialBar: {
                offsetY: 0,
                startAngle: -90,
                endAngle: 270,
                hollow: { margin: 5, size: '38%', background: 'transparent' },
                track: { background: '#f0f0f5', strokeWidth: '97%', margin: 5 },
                dataLabels: {
                    name: { show: true, fontSize: '13px', fontWeight: 600, offsetY: -8 },
                    value: { show: true, fontSize: '18px', fontWeight: 'bold', formatter: function(v) { return v + '%'; } }
                }
            }
        },
        labels: ['Active', 'Near EOS', 'EOS', 'Unknown'],
        colors: radialColors,
        tooltip: { theme: 'dark' }
    }).render();
}
</script>
@endsection
