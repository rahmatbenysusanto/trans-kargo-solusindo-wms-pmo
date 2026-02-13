@extends('layout.index')
@section('title', 'Detail Return To Client')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Return To Client Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('return.index') }}">Return</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-warning border-3 mb-4">
                <div class="card-header bg-light-subtle py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                <i class="ri-refresh-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark">{{ $outbound->number }}</h5>
                            <span class="badge bg-warning-subtle text-warning">Return Transaction</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('return.downloadExcel', ['id' => $outbound->id]) }}"
                            class="btn btn-success btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-excel-2-line label-icon align-middle fs-16 me-2"></i> Excel
                        </a>
                        <a href="{{ route('return.downloadPDF', ['id' => $outbound->id]) }}" target="_blank"
                            class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-pdf-line label-icon align-middle fs-16 me-2"></i> PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Transaction Overview -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle text-center text-md-start">
                                <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                                    <i class="ri-user-star-line text-warning fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Client Information</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $outbound->client->name }}</h5>
                                <p class="text-muted mb-0 small">Registered Stakeholder</p>
                            </div>
                        </div>

                        <!-- Logistics Details -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle text-center text-md-start">
                                <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                                    <i class="ri-truck-line text-info fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Courier Info</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $outbound->courier }}</h5>
                                <p class="text-muted mb-0 small">AWB: <span
                                        class="text-dark fw-medium">{{ $outbound->tracking_number }}</span></p>
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle text-center text-md-start">
                                <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                                    <i class="ri-map-pin-line text-success fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Return Site</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $outbound->site_location }}</h5>
                                <p class="text-muted mb-0 small">Handled by: <span
                                        class="text-dark fw-medium">{{ $outbound->received_by }}</span></p>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle text-center text-md-start">
                                <div class="d-flex align-items-center mb-2 justify-content-center justify-content-md-start">
                                    <i class="ri-calendar-event-line text-primary fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Internal Info</h6>
                                </div>
                                <h5 class="fs-15 mb-1">
                                    {{ \Carbon\Carbon::parse($outbound->delivery_date)->format('d M Y') }}</h5>
                                <p class="text-muted mb-0 small">Processed by: <span
                                        class="text-dark fw-medium">{{ $outbound->user->name }}</span></p>
                            </div>
                        </div>
                    </div>

                    @if ($outbound->remarks)
                        <div class="mt-4 p-3 bg-light rounded border-start border-warning border-3">
                            <div class="d-flex align-items-center">
                                <i class="ri-information-line text-warning fs-18 me-2"></i>
                                <span class="text-muted fw-medium small text-uppercase">Return Notes:</span>
                            </div>
                            <p class="mb-0 mt-1 text-dark">{{ $outbound->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-history-line text-warning fs-20 me-2"></i>
                        <h5 class="card-title mb-0">Returned Products</h5>
                        <span class="badge badge-soft-warning ms-2">{{ count($outboundDetail) }} Items</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Product Description</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th class="text-end">Condition</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outboundDetail as $index => $detail)
                                    <tr>
                                        <td><span class="text-muted fw-medium">{{ $index + 1 }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div
                                                            class="avatar-title bg-warning-subtle text-warning rounded text-uppercase fw-bold small">
                                                            RT
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0">{{ $detail->inventory->part_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-muted">{{ $detail->inventory->part_number }}</span></td>
                                        <td><code
                                                class="text-warning font-monospace">{{ $detail->inventory->serial_number }}</code>
                                        </td>
                                        <td class="text-end">
                                            @php
                                                $conditionClass = 'bg-success';
                                                if ($detail->condition == 'Damage') {
                                                    $conditionClass = 'bg-danger';
                                                }
                                                if ($detail->condition == 'Scrape') {
                                                    $conditionClass = 'bg-warning';
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $conditionClass }}">{{ $detail->condition ?? 'Good' }}</span>
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
@endsection
