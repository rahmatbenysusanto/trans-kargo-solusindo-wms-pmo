@extends('layout.index')
@section('title', 'Top Devices by Client')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-gradient-primary">High-Demand Inventory</h4>
                <p class="text-muted mb-0 fs-13">Top devices ranked by total stock units & client portfolio analysis</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-soft-primary text-primary fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-stack-line me-1"></i> {{ $totalUniqueParts }} Unique Parts
                </span>
                <span class="badge bg-soft-success text-success fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-database-2-line me-1"></i> {{ number_format($totalStockUnits) }} Total Units
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ===== KPI MINI ROW ===== --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card modern-card border-0 shadow-sm bg-gradient-dark text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="mdi mdi-barcode-scan fs-20"></i>
                    </div>
                    <div>
                        <span class="text-white-50 fs-11 text-uppercase fw-semibold">Unique Parts</span>
                        <h4 class="text-white mb-0 fw-bold">{{ $totalUniqueParts }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card modern-card border-0 shadow-sm bg-gradient-dark text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="mdi mdi-package-variant fs-20"></i>
                    </div>
                    <div>
                        <span class="text-white-50 fs-11 text-uppercase fw-semibold">Total Units</span>
                        <h4 class="text-white mb-0 fw-bold">{{ number_format($totalStockUnits) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card modern-card border-0 shadow-sm bg-gradient-dark text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="mdi mdi-account-group fs-20"></i>
                    </div>
                    <div>
                        <span class="text-white-50 fs-11 text-uppercase fw-semibold">Clients</span>
                        <h4 class="text-white mb-0 fw-bold">{{ $clientSummary->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card modern-card border-0 shadow-sm bg-gradient-dark text-white">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="mdi mdi-filter-variant fs-20"></i>
                    </div>
                    <div>
                        <span class="text-white-50 fs-11 text-uppercase fw-semibold">Filter</span>
                        <h4 class="text-white mb-0 fw-bold fs-16">{{ $selectedClient ? \Illuminate\Support\Str::limit(optional($clients->firstWhere('id', $selectedClient))->name ?? 'Client', 12) : 'All Clients' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== CHART + TABLE ROW ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-7">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-bar me-2 text-primary"></i>Top 12 Devices by Unit Count</h5>
            </div>
            <div class="card-body p-4">
                <div id="topdev_chart" data-colors='["#405189","#28a745","#17a2b8","#f1c40f","#e74c3c","#6f42c1","#fd7e14","#e83e8c","#20c997","#3498db","#2ecc71","#9b59b6"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-account-group-outline me-2 text-info"></i>Client Portfolio Summary</h5>
            </div>
            <div class="card-body p-0" style="max-height:420px; overflow-y:auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th class="ps-4">Client</th>
                            <th class="text-center">Parts</th>
                            <th class="text-center">Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientSummary as $cs)
                        <tr class="table-row-hover">
                            <td class="ps-4 fw-semibold">{{ $cs->client_name }}</td>
                            <td class="text-center">{{ $cs->part_count }}</td>
                            <td class="text-center fw-bold">{{ number_format($cs->total_qty) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN TABLE ===== --}}
<div class="row">
    <div class="col-12">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-trophy-variant me-2 text-warning"></i>Device Ranking Table</h5>
                    <p class="text-muted small mb-0 mt-1">Pagination-enabled ranking with real-time stock levels</p>
                </div>
                <form action="" method="GET" class="d-flex align-items-end gap-2">
                    <div class="input-group input-group-sm" style="width:260px;">
                        <span class="input-group-text bg-light border-light text-primary"><i class="mdi mdi-account-search-outline"></i></span>
                        <select class="form-select border-light bg-light fw-medium" name="client" onchange="this.form.submit()">
                            <option value="">-- All Clients --</option>
                            @foreach ($clients as $item)
                                <option value="{{ $item->id }}" {{ $selectedClient == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedClient)
                        <a href="{{ url()->current() }}" class="btn btn-sm btn-soft-danger"><i class="mdi mdi-refresh"></i></a>
                    @endif
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width:70px">Rank</th>
                                <th>Device / Part Name</th>
                                <th>Part Number</th>
                                <th class="text-center" style="width:130px">Total Units</th>
                                <th class="text-center" style="width:140px">Stock Level</th>
                                <th class="text-center" style="width:80px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory as $index => $item)
                            @php $rank = $inventory->firstItem() + $index; @endphp
                            <tr class="table-row-hover">
                                <td class="ps-4">
                                    @if($rank == 1) <span class="rank-badge gold">🥇</span>
                                    @elseif($rank == 2) <span class="rank-badge silver">🥈</span>
                                    @elseif($rank == 3) <span class="rank-badge bronze">🥉</span>
                                    @else <span class="rank-number text-muted fw-semibold">#{{ $rank }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="device-avatar me-3">
                                            <span class="device-avatar-text bg-soft-primary text-primary fw-bold">
                                                {{ strtoupper(substr($item->part_name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $item->part_name }}</h6>
                                            <small class="text-muted text-uppercase fs-11">Asset</small>
                                        </div>
                                    </div>
                                </td>
                                <td><code class="part-number-code">{{ $item->part_number }}</code></td>
                                <td class="text-center fw-bold fs-15">{{ number_format($item->total_unit) }}</td>
                                <td class="text-center">
                                    @php $maxVal = $inventory->first()->total_unit ?? 1; $width = min(100, ($item->total_unit / max(1,$maxVal)) * 100); @endphp
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <div class="progress progress-sm stock-progress-bar" style="height:6px; width:60px;">
                                            <div class="progress-bar bg-{{ $rank <= 3 ? 'success' : 'info' }} rounded-pill" style="width:{{ $width }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ round($width) }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('inbound.inventory.index', ['partNumber' => $item->part_number]) }}"
                                        class="btn btn-soft-info btn-sm shadow-none" data-bs-toggle="tooltip" title="View Inventory">
                                        <i class="mdi mdi-eye-outline fs-16"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">No device data found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-13">Showing {{ $inventory->firstItem() ?? 0 }}-{{ $inventory->lastItem() ?? 0 }} of {{ $inventory->total() }} entries</span>
                    {{ $inventory->appends(['client' => $selectedClient])->links() }}
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

var colors = getChartColorsArray("topdev_chart");
if (colors) {
    new ApexCharts(document.querySelector("#topdev_chart"), {
        series: [{ name: 'Total Units', data: {!! json_encode($chartSeries) !!} }],
        chart: { type: 'bar', height: 380, toolbar: { show: false }, animations: { enabled: true, speed: 800 } },
        plotOptions: { bar: { horizontal: true, borderRadius: 8, borderRadiusApplication: 'end', barHeight: '60%', distributed: true } },
        dataLabels: { enabled: true, textAnchor: 'start', offsetX: 8, style: { colors: ['#fff'], fontWeight: 'bold', fontSize: '12px' }, formatter: function(v) { return v; }, dropShadow: { enabled: true } },
        xaxis: { categories: {!! json_encode($chartLabels) !!}, labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '11px' } } },
        yaxis: { labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '12px' } } },
        grid: { borderColor: '#f0f0f0', strokeDashArray: 4, padding: { left: 20, right: 50, top: 0, bottom: 0 } },
        colors: colors,
        legend: { show: false },
        tooltip: { theme: 'dark' }
    }).render();
}
</script>
@endsection
