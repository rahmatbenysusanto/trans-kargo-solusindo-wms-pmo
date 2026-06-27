@extends('layout.index')
@section('title', 'Dashboard Overview')

@section('content')
<div class="dashboard-modern">
    {{-- ========== HEADER SECTION ========== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h3 class="fw-bold mb-1 text-gradient-primary">Operational Overview</h3>
                    <p class="text-muted mb-0 fs-14">
                        <i class="ri-calendar-event-line me-1"></i>
                        {{ \Carbon\Carbon::now()->format('F Y') }} — Real-time Warehouse Analytics
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-soft-primary text-primary fs-13 px-3 py-2 rounded-pill">
                        <i class="ri-database-2-line me-1"></i> Live Data
                    </span>
                    <span class="badge bg-soft-success text-success fs-13 px-3 py-2 rounded-pill">
                        <i class="ri-check-double-line me-1"></i> {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== KPI METRIC CARDS ========== --}}
    <div class="row g-4 mb-4">
        {{-- Inbound Dismantle --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-green">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <div class="kpi-icon bg-success">
                            <i class="mdi mdi-package-down"></i>
                        </div>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Inbound Dismantle</span>
                        <h2 class="kpi-value">{{ number_format($inboundDismantle) }}</h2>
                        <span class="kpi-badge text-success">
                            <i class="ri-arrow-up-circle-line"></i> This Month
                        </span>
                    </div>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

        {{-- Inbound Relocation --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-blue">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <div class="kpi-icon bg-info">
                            <i class="mdi mdi-swap-horizontal-bold"></i>
                        </div>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Inbound Relocation</span>
                        <h2 class="kpi-value">{{ number_format($inboundRelocation) }}</h2>
                        <span class="kpi-badge text-info">
                            <i class="ri-arrow-up-circle-line"></i> This Month
                        </span>
                    </div>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

        {{-- Total Outbound --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-purple">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <div class="kpi-icon bg-primary">
                            <i class="mdi mdi-truck-fast-outline"></i>
                        </div>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Total Outbound</span>
                        <h2 class="kpi-value">{{ number_format($outbound) }}</h2>
                        <span class="kpi-badge text-primary">
                            <i class="ri-arrow-up-circle-line"></i> This Month
                        </span>
                    </div>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>

        {{-- Total Return --}}
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card kpi-card-orange">
                <div class="kpi-card-body">
                    <div class="kpi-icon-wrapper">
                        <div class="kpi-icon bg-warning">
                            <i class="mdi mdi-undo-variant"></i>
                        </div>
                    </div>
                    <div class="kpi-info">
                        <span class="kpi-label">Total Return</span>
                        <h2 class="kpi-value">{{ number_format($return) }}</h2>
                        <span class="kpi-badge text-warning">
                            <i class="ri-arrow-up-circle-line"></i> This Month
                        </span>
                    </div>
                </div>
                <div class="kpi-glow"></div>
            </div>
        </div>
    </div>

    {{-- ========== CHARTS ROW 1: Stock Availability + Lifecycle ========== --}}
    <div class="row g-4 mb-4">
        {{-- Stock Availability Bar Chart --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card modern-card border-0 shadow-sm h-100">
                <div class="card-header-modern">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="mdi mdi-chart-bar me-2 text-success"></i>Stock Availability by Client
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Distribution of active inventory across stakeholders</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success-subtle text-success fs-12 px-2 py-1">
                            Total: {{ number_format($totalStock) }} Units
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="stock-bar-chart" data-colors='["#28a745","#20c997","#17a2b8","#6f42c1","#fd7e14","#e83e8c","#405189","#f1c40f","#e74c3c","#2ecc71"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        {{-- Lifecycle Donut Chart --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card modern-card border-0 shadow-sm h-100">
                <div class="card-header-modern">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="mdi mdi-lifebuoy me-2 text-primary"></i>Lifecycle Status
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Asset end-of-support distribution</p>
                    </div>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div id="lifecycle-donut-chart" data-colors='["#28a745","#f1c40f","#e74c3c","#95a5a6"]' class="apex-charts" dir="ltr"></div>
                    <div class="lifecycle-summary mt-3">
                        <div class="d-flex justify-content-around text-center">
                            <div>
                                <span class="fs-13 text-muted">Active</span>
                                <h6 class="mb-0 fw-bold text-success">{{ number_format($lifecycle->active) }}</h6>
                            </div>
                            <div>
                                <span class="fs-13 text-muted">Near EOS</span>
                                <h6 class="mb-0 fw-bold text-warning">{{ number_format($lifecycle->near_eos) }}</h6>
                            </div>
                            <div>
                                <span class="fs-13 text-muted">EOS</span>
                                <h6 class="mb-0 fw-bold text-danger">{{ number_format($lifecycle->eos) }}</h6>
                            </div>
                            <div>
                                <span class="fs-13 text-muted">Unknown</span>
                                <h6 class="mb-0 fw-bold text-secondary">{{ number_format($lifecycle->unknown) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== CHARTS ROW 2: Inbound vs Outbound Trend (2 Charts) ========== --}}
    <div class="row g-4 mb-4">
        {{-- Inbound Bar Chart --}}
        <div class="col-xl-6 col-lg-6">
            <div class="card modern-card border-0 shadow-sm h-100">
                <div class="card-header-modern">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="mdi mdi-package-down me-2 text-primary"></i>Items Received (Inbound)
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Monthly inbound — bar chart</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                        <i class="mdi mdi-calendar-month me-1"></i> 12 Months
                    </span>
                </div>
                <div class="card-body p-4">
                    <div id="inbound-bar-chart" data-colors='["#405189"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        {{-- Outbound Area/Line Chart --}}
        <div class="col-xl-6 col-lg-6">
            <div class="card modern-card border-0 shadow-sm h-100">
                <div class="card-header-modern">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="mdi mdi-truck-fast-outline me-2 text-success"></i>Items Shipped (Outbound)
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Monthly outbound — area line chart</p>
                    </div>
                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                        <i class="mdi mdi-calendar-month me-1"></i> 12 Months
                    </span>
                </div>
                <div class="card-body p-4">
                    <div id="outbound-area-chart" data-colors='["#28a745"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== DATA ROW: Top Devices + Stock Monitoring ========== --}}
    <div class="row g-4">
        {{-- Top Devices Table --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card modern-card border-0 shadow-sm h-100">
                <div class="card-header-modern">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="mdi mdi-trophy-variant me-2 text-warning"></i>Top Devices by Inventory Count
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Highest stock-keeping units across all clients</p>
                    </div>
                    <a href="{{ route('topDevices') }}" class="btn btn-sm btn-soft-primary rounded-pill px-3">
                        View All <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table modern-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 fs-13 text-muted fw-semibold text-uppercase" style="width:60px">#</th>
                                    <th class="fs-13 text-muted fw-semibold text-uppercase">Device / Part Name</th>
                                    <th class="fs-13 text-muted fw-semibold text-uppercase">Part Number</th>
                                    <th class="text-center fs-13 text-muted fw-semibold text-uppercase" style="width:120px">Total Units</th>
                                    <th class="text-center fs-13 text-muted fw-semibold text-uppercase" style="width:80px">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDevices as $index => $device)
                                <tr class="table-row-hover">
                                    <td class="ps-4">
                                        @if($index == 0)
                                            <span class="rank-badge gold">🥇</span>
                                        @elseif($index == 1)
                                            <span class="rank-badge silver">🥈</span>
                                        @elseif($index == 2)
                                            <span class="rank-badge bronze">🥉</span>
                                        @else
                                            <span class="rank-number text-muted fw-semibold">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="device-avatar me-3">
                                                <span class="device-avatar-text bg-soft-primary text-primary fw-bold">
                                                    {{ strtoupper(substr($device->part_name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fs-14 fw-semibold">{{ $device->part_name }}</h6>
                                                <small class="text-muted text-uppercase fs-11">Asset</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="part-number-code">{{ $device->part_number }}</code>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold fs-15">{{ number_format($device->total_unit) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $width = min(100, ($device->total_unit / max(1, $topDevices->first()->total_unit ?? 1)) * 100);
                                        @endphp
                                        <div class="progress progress-sm stock-progress-bar" style="height:6px; width:60px; margin:0 auto;">
                                            <div class="progress-bar bg-success rounded-pill" style="width:{{ $width }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="mdi mdi-package-variant-closed fs-40 text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">No device data available</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock Monitoring Sidebar --}}
        <div class="col-xl-4 col-lg-5">
            <div class="row g-4">
                {{-- Mini KPI Cards --}}
                <div class="col-12">
                    <div class="card modern-card border-0 shadow-sm bg-gradient-dark text-white overflow-hidden position-relative">
                        <div class="card-body p-4">
                            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                                <i class="mdi mdi-warehouse fs-64"></i>
                            </div>
                            <h6 class="text-white-50 mb-3 text-uppercase fs-12 fw-semibold">Stock Monitoring Summary</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                        <span class="fs-12 text-white-50">Total Units</span>
                                        <h3 class="text-white mb-0 fw-bold">{{ number_format($totalItems) }}</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                        <span class="fs-12 text-white-50">Unique Parts</span>
                                        <h3 class="text-white mb-0 fw-bold">{{ number_format($uniqueParts) }}</h3>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3 bg-white bg-opacity-10">
                                        <span class="fs-12 text-white-50">Serialized Stock (SN Traceability)</span>
                                        <h3 class="text-white mb-0 fw-bold">{{ number_format($totalSerialNumbers) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('stockMonitoring') }}" class="btn btn-light btn-sm w-100 mt-3 fw-semibold">
                                <i class="ri-eye-line me-1"></i> View Full Stock Monitoring
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="col-12">
                    <div class="card modern-card border-0 shadow-sm">
                        <div class="card-header-modern">
                            <h6 class="mb-0 fw-bold">
                                <i class="mdi mdi-link-variant me-2 text-info"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('inbound.receiving.index') }}" class="btn btn-soft-success btn-sm text-start">
                                    <i class="mdi mdi-package-down me-2"></i> Inbound Receiving
                                </a>
                                <a href="{{ route('outbound.index') }}" class="btn btn-soft-primary btn-sm text-start">
                                    <i class="mdi mdi-truck-fast-outline me-2"></i> Outbound Shipment
                                </a>
                                <a href="{{ route('return.index') }}" class="btn btn-soft-warning btn-sm text-start">
                                    <i class="mdi mdi-undo-variant me-2"></i> Return to Client
                                </a>
                                <a href="{{ route('inbound.inventory.index') }}" class="btn btn-soft-info btn-sm text-start">
                                    <i class="mdi mdi-format-list-bulleted me-2"></i> Inventory List
                                </a>
                            </div>
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
(function() {
    // ==================== CHART COLOR HELPER ====================
    function getChartColorsArray(elId) {
        var el = document.getElementById(elId);
        if (!el) return null;
        var raw = el.getAttribute("data-colors");
        if (!raw) return null;
        try {
            var colors = JSON.parse(raw);
            return colors.map(function(c) {
                c = c.replace(/\s/g, '');
                if (c.indexOf(',') === -1) {
                    var v = getComputedStyle(document.documentElement).getPropertyValue(c);
                    return (v && v.trim()) ? v.trim() : c;
                }
                var parts = c.split(',');
                if (parts.length === 2) {
                    var base = getComputedStyle(document.documentElement).getPropertyValue(parts[0]);
                    return 'rgba(' + (base || '0,0,0') + ',' + parts[1] + ')';
                }
                return c;
            });
        } catch (e) {
            return null;
        }
    }

    // ==================== STOCK BAR CHART ====================
    var barColors = getChartColorsArray("stock-bar-chart");
    if (barColors && document.querySelector("#stock-bar-chart")) {
        var barOptions = {
            chart: {
                type: 'bar',
                height: 360,
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 8,
                    borderRadiusApplication: 'end',
                    barHeight: '50%',
                    distributed: true,
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 10,
                style: { colors: ['#fff'], fontWeight: 'bold', fontSize: '12px' },
                formatter: function(val) { return val + ' Units'; },
                dropShadow: { enabled: true, top: 1, left: 1, blur: 2, opacity: 0.3 }
            },
            series: [{ name: 'Stock Level', data: {!! json_encode($dataStock) !!} }],
            colors: barColors,
            grid: {
                borderColor: '#f0f0f0',
                strokeDashArray: 4,
                padding: { left: 20, right: 50, top: 0, bottom: 0 },
                xaxis: { lines: { show: true } }
            },
            xaxis: {
                categories: {!! json_encode($dataClient) !!},
                labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '12px' } }
            },
            yaxis: {
                labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '12px' } }
            },
            tooltip: { theme: 'dark', y: { formatter: function(v) { return v + ' Units'; } } }
        };
        new ApexCharts(document.querySelector("#stock-bar-chart"), barOptions).render();
    }

    // ==================== LIFECYCLE DONUT CHART ====================
    var donutColors = getChartColorsArray("lifecycle-donut-chart");
    if (donutColors && document.querySelector("#lifecycle-donut-chart")) {
        var donutOptions = {
            series: {!! json_encode($lifecycleChart) !!},
            chart: { type: 'donut', height: 280, toolbar: { show: false } },
            labels: ['Active', 'Near EOS', 'EOS Reached', 'Unknown'],
            colors: donutColors,
            stroke: { width: 0 },
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '13px', fontWeight: 600, offsetY: -5 },
                            value: { show: true, fontSize: '20px', fontWeight: 'bold', offsetY: 5 },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                fontWeight: 600,
                                color: '#74788d',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontWeight: 500,
                itemMargin: { horizontal: 8, vertical: 5 },
                markers: { width: 10, height: 10, radius: 4 }
            },
            dataLabels: { enabled: false },
            tooltip: { theme: 'dark' }
        };
        new ApexCharts(document.querySelector("#lifecycle-donut-chart"), donutOptions).render();
    }

    // ==================== INBOUND BAR CHART (Left) ====================
    var inboundBarColors = getChartColorsArray("inbound-bar-chart");
    if (inboundBarColors && document.querySelector("#inbound-bar-chart")) {
        var inboundBarOptions = {
            series: [{ name: 'Items Received', data: {!! json_encode($dataInbound) !!} }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } },
                animations: { enabled: true, easing: 'easeinout', speed: 1000 }
            },
            plotOptions: {
                bar: {
                    columnWidth: '55%',
                    borderRadius: 8,
                    borderRadiusApplication: 'end',
                    distributed: false,
                    colors: {
                        backgroundBarColors: ['#f8f9fc'],
                        backgroundBarRadius: 8
                    }
                }
            },
            colors: ['#405189'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    gradientToColors: ['#6c7ed8'],
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100]
                }
            },
            dataLabels: {
                enabled: true,
                position: 'top',
                offsetY: -4,
                style: { colors: ['#405189'], fontWeight: 600, fontSize: '11px' },
                formatter: function(v) { return v > 0 ? (v >= 1000 ? (v/1000).toFixed(1)+'k' : v) : ''; }
            },
            xaxis: {
                categories: {!! json_encode($dataMonths) !!},
                axisTicks: { show: false },
                axisBorder: { show: false },
                labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '11px' }, rotate: -30 }
            },
            yaxis: {
                title: { text: 'Qty', style: { color: '#adb5bd', fontWeight: 500, fontSize: '11px' } },
                labels: { style: { colors: '#74788d', fontSize: '11px' } }
            },
            grid: {
                show: true,
                borderColor: '#f0f0f0',
                strokeDashArray: 4,
                padding: { top: 20, right: 10, bottom: 0, left: 10 }
            },
            legend: { show: false },
            tooltip: {
                theme: 'dark',
                y: { formatter: function(v) { return v + ' Units'; } }
            }
        };
        new ApexCharts(document.querySelector("#inbound-bar-chart"), inboundBarOptions).render();
    }

    // ==================== OUTBOUND AREA CHART (Right) ====================
    var outboundAreaColors = getChartColorsArray("outbound-area-chart");
    if (outboundAreaColors && document.querySelector("#outbound-area-chart")) {
        var outboundAreaOptions = {
            series: [{ name: 'Items Shipped', data: {!! json_encode($dataOutbound) !!} }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } },
                animations: { enabled: true, easing: 'easeinout', speed: 1000 },
                dropShadow: {
                    enabled: true,
                    color: '#28a745',
                    top: 5,
                    left: 0,
                    blur: 6,
                    opacity: 0.12
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#28a745']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#28a745', opacity: 0.45 },
                        { offset: 100, color: '#28a745', opacity: 0.02 }
                    ]
                }
            },
            markers: {
                size: 5,
                strokeWidth: 0,
                hover: { size: 8 },
                colors: ['#28a745']
            },
            dataLabels: {
                enabled: true,
                offsetY: -6,
                style: { colors: ['#28a745'], fontWeight: 600, fontSize: '11px' },
                formatter: function(v) { return v > 0 ? (v >= 1000 ? (v/1000).toFixed(1)+'k' : v) : ''; },
                background: { enabled: true, foreColor: '#fff', padding: 4, borderRadius: 4, borderWidth: 0 }
            },
            xaxis: {
                categories: {!! json_encode($dataMonths) !!},
                axisTicks: { show: false },
                axisBorder: { show: false },
                labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '11px' }, rotate: -30 }
            },
            yaxis: {
                title: { text: 'Qty', style: { color: '#adb5bd', fontWeight: 500, fontSize: '11px' } },
                labels: { style: { colors: '#74788d', fontSize: '11px' } }
            },
            grid: {
                show: true,
                borderColor: '#f0f0f0',
                strokeDashArray: 4,
                padding: { top: 20, right: 10, bottom: 0, left: 10 }
            },
            legend: { show: false },
            tooltip: {
                theme: 'dark',
                y: { formatter: function(v) { return v + ' Units'; } }
            }
        };
        new ApexCharts(document.querySelector("#outbound-area-chart"), outboundAreaOptions).render();
    }
})();
</script>
@endsection
