@extends('layout.index')
@section('title', 'Detail Back To WH')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Back To Warehouse Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('backToWh.index') }}">Back To WH</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-teal py-3 d-flex justify-content-between align-items-center"
                    style="background-color: #0d9488 !important;">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title bg-white-subtle text-white rounded-circle fs-20">
                                <i class="ri-archive-drawer-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-white">{{ $backToWh->number }}</h5>
                            <span class="badge bg-white-subtle text-white">Back To Warehouse</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('backToWh.downloadExcel', ['id' => $backToWh->id]) }}"
                            class="btn btn-success btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-excel-2-line label-icon align-middle fs-16 me-2"></i> Excel
                        </a>
                        <a href="{{ route('backToWh.downloadPDF', ['id' => $backToWh->id]) }}" target="_blank"
                            class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-pdf-line label-icon align-middle fs-16 me-2"></i> PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Reason -->
                        <div class="col-md-4">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-question-answer-line text-teal fs-20 me-2" style="color: #0d9488;"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Return Reason</h6>
                                </div>
                                <p class="fs-14 mb-0">{{ $backToWh->reason }}</p>
                            </div>
                        </div>

                        <!-- Received Info -->
                        <div class="col-md-4">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-user-received-line text-info fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Received Information</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $backToWh->received_by ?? '-' }}</h5>
                                <p class="text-muted mb-0 small">
                                    {{ $backToWh->received_at ? \Carbon\Carbon::parse($backToWh->received_at)->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Processing Info -->
                        <div class="col-md-4">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-calendar-event-line text-warning fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Processing Info</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $backToWh->created_at->format('d M Y') }}</h5>
                                <p class="text-muted mb-0 small">Handled by: <span
                                        class="text-dark fw-medium">{{ $backToWh->user->name ?? '-' }}</span></p>
                            </div>
                        </div>
                    </div>

                    @if ($backToWh->remarks)
                        <div class="mt-4 p-3 bg-light rounded border-start border-teal border-3" style="border-color: #0d9488 !important;">
                            <div class="d-flex align-items-center">
                                <i class="ri-information-line text-teal fs-18 me-2" style="color: #0d9488;"></i>
                                <span class="text-muted fw-medium small text-uppercase">Remarks:</span>
                            </div>
                            <p class="mb-0 mt-1 text-dark">{{ $backToWh->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-box-3-line text-teal fs-20 me-2" style="color: #0d9488;"></i>
                        <h5 class="card-title mb-0">Returned Product List</h5>
                        <span class="badge badge-soft-info ms-2">{{ count($backToWhDetail) }} Items</span>
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
                                    <th>Condition</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($backToWhDetail as $index => $detail)
                                    <tr>
                                        <td><span class="text-muted fw-medium">{{ $index + 1 }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-teal-subtle text-teal rounded"
                                                            style="color: #0d9488; background-color: rgba(13, 148, 136, 0.1);">
                                                            <i class="ri-box-3-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0">{{ $detail->part_name }}</h6>
                                                    <p class="text-muted small mb-0">{{ $detail->inventory->part_description ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-muted">{{ $detail->part_number }}</span></td>
                                        <td><code class="text-primary font-monospace">{{ $detail->serial_number }}</code></td>
                                        <td>
                                            @php
                                                $conditionClass = match ($detail->condition) {
                                                    'Good' => 'bg-success',
                                                    'Scrape' => 'bg-warning text-dark',
                                                    'Damage' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $conditionClass }} px-3 py-2">{{ $detail->condition ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="min-width: 150px;">
                                                {{ $detail->reason ?? '-' }}
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
    </div>
@endsection
