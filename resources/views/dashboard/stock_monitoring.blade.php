@extends('layout.index')
@section('title', 'Stock Monitoring')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-gradient-primary">Stock Monitoring</h4>
                <p class="text-muted mb-0 fs-13">Real-time inventory tracking, condition breakdown & low stock alerts</p>
            </div>
            <div class="d-flex gap-2">
                @if($lowStockCount > 0)
                <span class="badge bg-danger px-3 py-2 rounded-pill fs-13">
                    <i class="ri-alert-line me-1"></i> {{ $lowStockCount }} Low Stock Alerts
                </span>
                @endif
                <span class="badge bg-soft-success text-success fs-13 px-3 py-2 rounded-pill">
                    <i class="ri-check-double-line me-1"></i> Live
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ===== KPI ROW ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-purple">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-primary"><i class="mdi mdi-package-variant-closed"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label">Total Stock Units</span>
                    <h2 class="kpi-value">{{ number_format($totalItems) }}</h2>
                    <span class="kpi-badge text-primary">All Active Inventory</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-blue">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-info"><i class="mdi mdi-alpha-p-circle"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label">Unique Parts</span>
                    <h2 class="kpi-value">{{ number_format($uniqueParts) }}</h2>
                    <span class="kpi-badge text-info">Distinct SKU Catalog</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card kpi-card-green">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper"><div class="kpi-icon bg-success"><i class="mdi mdi-barcode-scan"></i></div></div>
                <div class="kpi-info">
                    <span class="kpi-label">Serialized Stock</span>
                    <h2 class="kpi-value">{{ number_format($totalSerialNumbers) }}</h2>
                    <span class="kpi-badge text-success">With SN Traceability</span>
                </div>
            </div>
            <div class="kpi-glow"></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card @if($lowStockCount > 0)" style="border-top:3px solid #e74c3c;"@else kpi-card-green @endif">
            <div class="kpi-card-body">
                <div class="kpi-icon-wrapper">
                    <div class="kpi-icon @if($lowStockCount > 0) bg-danger @else bg-success @endif">
                        <i class="mdi @if($lowStockCount > 0) mdi-alert-octagon @else mdi-check-circle-outline @endif"></i>
                    </div>
                </div>
                <div class="kpi-info">
                    <span class="kpi-label @if($lowStockCount > 0)" style="color:#e74c3c;"@endif">⚠ Low Stock Items (≤5)</span>
                    <h2 class="kpi-value @if($lowStockCount > 0) text-danger @endif">{{ $lowStockCount }}</h2>
                    <span class="kpi-badge @if($lowStockCount > 0) text-danger @else text-success @endif">
                        {{ $lowStockCount > 0 ? 'Need Restock' : 'All Good' }}
                    </span>
                </div>
            </div>
            @if($lowStockCount > 0)
            <div class="kpi-glow" style="background:linear-gradient(90deg,#e74c3c,#f07167);"></div>
            @else
            <div class="kpi-glow"></div>
            @endif
        </div>
    </div>
</div>

{{-- ===== CHARTS + BREAKDOWN ROW ===== --}}
<div class="row g-4 mb-4">
    {{-- Top Parts Chart --}}
    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-chart-bar me-2 text-primary"></i>Top 10 Parts by Quantity</h5>
            </div>
            <div class="card-body p-4">
                <div id="top_parts_chart" data-colors='["#405189","#28a745","#17a2b8","#f1c40f","#e74c3c","#6f42c1","#fd7e14","#e83e8c","#20c997","#3498db"]' class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>

    {{-- Condition & Status Breakdown --}}
    <div class="col-xl-3 col-lg-6">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-shield-check-outline me-2 text-success"></i>Condition</h5>
            </div>
            <div class="card-body p-3">
                @forelse($conditionStats as $cond)
                @php
                    $condTotal = $conditionStats->sum('count');
                    $condPct = $condTotal > 0 ? round(($cond->count / $condTotal) * 100) : 0;
                    $condColor = match(strtolower($cond->condition ?? '')) {
                        'good' => 'success', 'damaged' => 'danger', 'fair' => 'warning',
                        default => 'info'
                    };
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold fs-13">{{ $cond->condition ?? 'N/A' }}</span>
                        <span class="text-muted fs-13">{{ number_format($cond->count) }} ({{ $condPct }}%)</span>
                    </div>
                    <div class="progress" style="height:8px; border-radius:10px;">
                        <div class="progress-bar bg-{{ $condColor }} rounded-pill" style="width:{{ max(5,$condPct) }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No condition data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6">
        <div class="card modern-card border-0 shadow-sm h-100">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-tag-outline me-2 text-info"></i>Status</h5>
            </div>
            <div class="card-body p-3">
                @forelse($statusStats as $st)
                @php
                    $stTotal = $statusStats->sum('count');
                    $stPct = $stTotal > 0 ? round(($st->count / $stTotal) * 100) : 0;
                    $stColor = match(strtolower($st->status ?? '')) {
                        'available' => 'success', 'reserved' => 'warning', 'in_use' => 'primary',
                        'damaged' => 'danger', 'quarantine' => 'dark',
                        default => 'info'
                    };
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold fs-13">{{ $st->status ?? 'N/A' }}</span>
                        <span class="text-muted fs-13">{{ number_format($st->count) }} ({{ $stPct }}%)</span>
                    </div>
                    <div class="progress" style="height:8px; border-radius:10px;">
                        <div class="progress-bar bg-{{ $stColor }} rounded-pill" style="width:{{ max(5,$stPct) }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3">No status data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ===== LOW STOCK ALERTS + CLIENT BREAKDOWN ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-alert-octagon-outline me-2 text-danger"></i>⚠ Low Stock Alerts (≤ 5 units)</h5>
                    <p class="text-muted small mb-0 mt-1">Items requiring immediate replenishment</p>
                </div>
                @if($lowStockCount > 0)
                <span class="badge bg-danger px-3 py-2 rounded-pill">{{ $lowStockCount }} Items</span>
                @endif
            </div>
            <div class="card-body p-0" style="max-height:340px; overflow-y:auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th class="ps-4">Part Name</th>
                            <th>Serial Number</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Condition</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $low)
                        <tr class="table-row-hover">
                            <td class="ps-4 fw-semibold fs-14">{{ $low->part_name }}</td>
                            <td><code class="part-number-code">{{ $low->serial_number ?? '-' }}</code></td>
                            <td class="text-center fw-bold text-danger">{{ $low->qty }}</td>
                            <td class="text-center"><span class="badge bg-info-subtle text-info rounded-pill px-2">{{ $low->condition ?? 'N/A' }}</span></td>
                            <td class="text-center"><span class="badge bg-warning-subtle text-warning rounded-pill px-2">{{ $low->status ?? 'N/A' }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">✅ No low stock items — all inventory well stocked.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-account-group-outline me-2 text-primary"></i>Stock by Client</h5>
            </div>
            <div class="card-body p-0" style="max-height:340px; overflow-y:auto;">
                <table class="table modern-table align-middle mb-0">
                    <thead class="sticky-top bg-light">
                        <tr>
                            <th class="ps-4">Client</th>
                            <th class="text-center">Total Units</th>
                            <th>Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxClientStock = $clientStock->max('total_qty') ?? 1; @endphp
                        @foreach($clientStock as $cs)
                        <tr class="table-row-hover">
                            <td class="ps-4 fw-semibold">{{ $cs->client_name ?? 'Unknown' }}</td>
                            <td class="text-center fw-bold">{{ number_format($cs->total_qty) }}</td>
                            <td>
                                @php $csPct = round(($cs->total_qty / max(1,$maxClientStock)) * 100); @endphp
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:6px; border-radius:10px;">
                                        <div class="progress-bar bg-primary rounded-pill" style="width:{{ max(3,$csPct) }}%"></div>
                                    </div>
                                    <small>{{ $csPct }}%</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN STOCK TABLE ===== --}}
<div class="row">
    <div class="col-12">
        <div class="card modern-card border-0 shadow-sm">
            <div class="card-header-modern">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-format-list-bulleted me-2 text-primary"></i>Complete Stock Catalog</h5>
                    <p class="text-muted small mb-0 mt-1">Click <strong>Detail</strong> to view serial numbers per part</p>
                </div>
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">{{ $uniqueParts }} Products</span>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="stock-table" class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Part Name</th>
                                <th>Part Number</th>
                                <th class="text-center" style="width:130px">Stock Qty</th>
                                <th class="text-center" style="width:140px">Stock Level</th>
                                <th class="text-center" style="width:100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr class="table-row-hover">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="device-avatar me-3">
                                            <span class="device-avatar-text bg-primary-subtle text-primary fw-bold">
                                                {{ strtoupper(substr($stock->part_name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="fw-semibold fs-14">{{ $stock->part_name }}</div>
                                    </div>
                                </td>
                                <td><code class="part-number-code">{{ $stock->part_number }}</code></td>
                                <td class="text-center fw-bold fs-15">{{ number_format($stock->total_qty) }}</td>
                                <td class="text-center">
                                    @php
                                        $maxQty = $stocks->max('total_qty') ?? 1;
                                        $pct = ($stock->total_qty / max(1,$maxQty)) * 100;
                                        $barColor = $pct > 50 ? 'success' : ($pct > 20 ? 'info' : 'warning');
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 justify-content-center">
                                        <div class="progress flex-grow-1" style="height:6px; max-width:80px; border-radius:10px;">
                                            <div class="progress-bar bg-{{ $barColor }} rounded-pill" style="width:{{ max(3,$pct) }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ round($pct) }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-soft-info shadow-none rounded-pill px-3 btn-detail"
                                            data-name="{{ $stock->part_name }}" data-number="{{ $stock->part_number }}">
                                        <i class="ri-eye-line me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="snModal" tabindex="-1" aria-labelledby="snModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-gradient-dark text-white px-4 py-3">
                <h5 class="modal-title fw-bold" id="snModalLabel"><i class="mdi mdi-barcode-scan me-1"></i> Serial Number Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div><h6 class="text-muted text-uppercase fw-semibold mb-1 fs-12">Product</h6><h5 id="modal-part-name" class="fw-bold mb-0 text-primary"></h5></div>
                    <div class="text-end"><h6 class="text-muted text-uppercase fw-semibold mb-1 fs-12">Part Number</h6><h5 id="modal-part-number" class="fw-bold mb-0"></h5></div>
                </div>
                <hr>
                <div class="table-responsive" style="max-height:420px;">
                    <table class="table modern-table table-sm align-middle mb-0">
                        <thead class="sticky-top bg-light"><tr><th>Serial Number</th><th>Bin Location</th><th class="text-center">Status</th><th class="text-center">Condition</th></tr></thead>
                        <tbody id="sn-list"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light-subtle">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('.btn-detail').on('click', function() {
        var partName = $(this).data('name'), partNumber = $(this).data('number');
        $('#modal-part-name').text(partName); $('#modal-part-number').text(partNumber);
        $('#sn-list').html('<tr><td colspan="4" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> Loading...</td></tr>');
        $('#snModal').modal('show');
        $.ajax({
            url: "{{ route('stockMonitoring.detail') }}", type: "GET",
            data: { part_name: partName, part_number: partNumber },
            success: function(r) {
                var h = '';
                if(r.length > 0) {
                    $.each(r, function(i, item) {
                        var sb = item.status == 'Available' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning';
                        var cb = item.condition == 'Good' ? 'bg-success-subtle text-success' : (item.condition == 'Damaged' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info');
                        h += '<tr class="table-row-hover"><td><code class="text-primary fw-medium">'+(item.serial_number||'-')+'</code></td>' +
                            '<td><i class="ri-map-pin-2-line text-muted me-1"></i>'+(item.bin_name||'Unassigned')+'</td>' +
                            '<td class="text-center"><span class="badge rounded-pill px-2 '+sb+'">'+(item.status||'N/A')+'</span></td>' +
                            '<td class="text-center"><span class="badge rounded-pill px-2 '+cb+'">'+(item.condition||'N/A')+'</span></td></tr>';
                    });
                } else { h = '<tr><td colspan="4" class="text-center py-4 text-muted">No serial numbers found.</td></tr>'; }
                $('#sn-list').html(h);
            },
            error: function() { $('#sn-list').html('<tr><td colspan="4" class="text-center py-4 text-danger">Failed to load.</td></tr>'); }
        });
    });
    if($('#stock-table').length) { $('#stock-table').DataTable({ "pageLength": 10, "order": [[2,"desc"]], "language": { "search": '<i class="ri-search-line me-1"></i>', "searchPlaceholder": "Search parts..." } }); }
});

(function() {
    function getColors(e) {
        var t = document.getElementById(e); if (!t) return null;
        var a = t.getAttribute("data-colors"); if (!a) return null;
        return JSON.parse(a).map(function(c) {
            c = c.replace(/\s/g,'');
            if (c.indexOf(',')===-1) return getComputedStyle(document.documentElement).getPropertyValue(c)||c;
            var p = c.split(','); return 'rgba('+getComputedStyle(document.documentElement).getPropertyValue(p[0])+','+p[1]+')';
        });
    }
    var tc = getColors("top_parts_chart");
    if (tc) new ApexCharts(document.querySelector("#top_parts_chart"), {
        series: [{ name: 'Quantity', data: {!! json_encode($topPartsChart->pluck('total_qty')->toArray()) !!} }],
        chart: { type: 'bar', height: 360, toolbar: { show: false }, animations: { enabled: true, speed: 800 } },
        plotOptions: { bar: { horizontal: true, borderRadius: 8, borderRadiusApplication: 'end', barHeight: '55%', distributed: true } },
        dataLabels: { enabled: true, textAnchor: 'start', offsetX: 8, style: { colors: ['#fff'], fontWeight: 'bold', fontSize: '12px' }, formatter: function(v){return v;}, dropShadow: { enabled: true } },
        xaxis: { categories: {!! json_encode($topPartsChart->pluck('part_name')->toArray()) !!}, labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '11px' } } },
        yaxis: { labels: { style: { colors: '#74788d', fontWeight: 500, fontSize: '12px' } } },
        grid: { borderColor: '#f0f0f0', strokeDashArray: 4, padding: { left: 20, right: 50, top: 0, bottom: 0 } },
        colors: tc, legend: { show: false }, tooltip: { theme: 'dark' }
    }).render();
})();
</script>
@endsection
