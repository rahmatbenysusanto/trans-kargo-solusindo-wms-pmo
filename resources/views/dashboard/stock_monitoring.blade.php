@extends('layout.index')
@section('title', 'Stock Monitoring')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Stock Monitoring</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stock Monitoring</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Total Items -->
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Total Stock Units</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary text-white rounded-3 fs-24">
                                <i class="mdi mdi-package-variant-closed"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h2 class="mb-2 fw-bold counter-value" data-target="{{ $totalItems }}">
                            {{ number_format($totalItems) }}</h2>
                        <span class="badge bg-primary-subtle text-primary fs-12">Total Quantity</span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm rounded-0" style="height: 4px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Unique Parts -->
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Unique Parts</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-info text-white rounded-3 fs-24">
                                <i class="mdi mdi-alpha-p-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h2 class="mb-2 fw-bold counter-value" data-target="{{ $uniqueParts }}">
                            {{ number_format($uniqueParts) }}</h2>
                            <span class="badge bg-info-subtle text-info fs-12">Catalog Items</span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm rounded-0" style="height: 4px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Serialized Stock -->
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Serialized Stock</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success text-white rounded-3 fs-24">
                                <i class="mdi mdi-barcode-scan"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-3">
                    <div>
                        <h2 class="mb-2 fw-bold counter-value" data-target="{{ $totalSerialNumbers }}">
                            {{ number_format($totalSerialNumbers) }}</h2>
                            <span class="badge bg-success-subtle text-success fs-12">With SN Traceability</span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm rounded-0" style="height: 4px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 align-items-center d-flex bg-light-subtle">
                <h4 class="card-title mb-0 flex-grow-1">Stock Availability by Part Name</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="stock-table" class="table table-hover align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Part Name</th>
                                <th scope="col">Part Number</th>
                                <th scope="col" class="text-center">Stock Quantity</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs me-2">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-13">
                                                {{ substr($stock->part_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 fw-semibold">{{ $stock->part_name }}</div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-body">{{ $stock->part_number }}</span></td>
                                <td class="text-center">
                                    <span class="fw-bold fs-14">{{ number_format($stock->total_qty) }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-soft-info waves-effect shadow-none btn-detail" 
                                            data-name="{{ $stock->part_name }}" 
                                            data-number="{{ $stock->part_number }}">
                                        <i class="ri-eye-line align-middle me-1"></i> Detail
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

<!-- Modal Detail -->
<div class="modal fade" id="snModal" tabindex="-1" aria-labelledby="snModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary p-3">
                <h5 class="modal-title text-white" id="snModalLabel">Serial Number Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase fw-semibold mb-1 fs-12">Product</h6>
                        <h5 id="modal-part-name" class="fw-bold mb-0 text-primary"></h5>
                    </div>
                    <div class="text-end">
                        <h6 class="text-muted text-uppercase fw-semibold mb-1 fs-12">Part Number</h6>
                        <h5 id="modal-part-number" class="fw-bold mb-0"></h5>
                    </div>
                </div>
                <hr class="text-muted opacity-25">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-striped align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Serial Number</th>
                                <th>Bin Location</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Condition</th>
                            </tr>
                        </thead>
                        <tbody id="sn-list">
                            <!-- Data populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function() {
    $('.btn-detail').on('click', function() {
        var partName = $(this).data('name');
        var partNumber = $(this).data('number');
        
        $('#modal-part-name').text(partName);
        $('#modal-part-number').text(partNumber);
        $('#sn-list').html('<tr><td colspan="4" class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</td></tr>');
        
        $('#snModal').modal('show');
        
        $.ajax({
            url: "{{ route('stockMonitoring.detail') }}",
            type: "GET",
            data: {
                part_name: partName,
                part_number: partNumber
            },
            success: function(response) {
                var html = '';
                if(response.length > 0) {
                    $.each(response, function(index, item) {
                        html += '<tr>' +
                            '<td><code class="text-primary">' + (item.serial_number || '-') + '</code></td>' +
                            '<td><i class="ri-map-pin-2-line text-muted me-1"></i>' + (item.bin_name || '-') + '</td>' +
                            '<td class="text-center"><span class="badge bg-info-subtle text-info">' + item.status + '</span></td>' +
                            '<td class="text-center"><span class="badge bg-success-subtle text-success">' + item.condition + '</span></td>' +
                            '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center text-muted italic">No serial numbers found</td></tr>';
                }
                $('#sn-list').html(html);
            }
        });
    });

    if($('#stock-table').length > 0) {
        $('#stock-table').DataTable({
            "pageLength": 10,
            "order": [[ 2, "desc" ]]
        });
    }
});
</script>
@endsection
