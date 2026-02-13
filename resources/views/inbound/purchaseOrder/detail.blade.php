@extends('layout.index')
@section('title', 'Receiving Detail')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Receiving Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('inbound.receiving.index') }}">Inbound</a></li>
                        <li class="breadcrumb-item">Receiving</li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title bg-white-subtle text-white rounded-circle fs-20">
                                <i class="ri-survey-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-white">{{ $inbound->number }}</h5>
                            <span class="badge bg-white-subtle text-white">Receiving Transaction</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('inbound.receiving.downloadExcel', ['id' => $inbound->id]) }}"
                            class="btn btn-success btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-excel-2-line label-icon align-middle fs-16 me-2"></i> Excel
                        </a>
                        <a href="{{ route('inbound.receiving.downloadPDF', ['id' => $inbound->id]) }}" target="_blank"
                            class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-pdf-line label-icon align-middle fs-16 me-2"></i> PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-user-star-line text-primary fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Client Information</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $inbound->client->name }}</h5>
                                <p class="text-muted mb-0 small">Status: <span
                                        class="badge bg-info-subtle text-info">{{ $inbound->owner_status }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-map-pin-line text-success fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Destination / Site</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $inbound->site_location }}</h5>
                                <p class="text-muted mb-0 small">Inbound Type: <span
                                        class="text-dark fw-medium">{{ $inbound->inbound_type }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-calendar-event-line text-warning fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Transaction Date</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ \Carbon\Carbon::parse($inbound->created_at)->format('d M Y') }}
                                </h5>
                                <p class="text-muted mb-0 small">Time: <span
                                        class="text-dark fw-medium">{{ \Carbon\Carbon::parse($inbound->created_at)->format('H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom d-flex align-items-center">
                    <i class="ri-box-3-line text-info fs-20 me-2"></i>
                    <h5 class="card-title mb-0">Received Product List</h5>
                    <span class="badge bg-info ms-2">{{ count($inboundDetail) }} Items</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Part Description</th>
                                    <th>Part No</th>
                                    <th>Serial No</th>
                                    <th>Condition</th>
                                    <th>Lifecycle Dates</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inboundDetail as $detail)
                                    <tr>
                                        <td><span class="text-muted fw-medium">{{ $loop->iteration }}</span></td>
                                        <td class="fw-medium text-dark">{{ $detail->part_name }}</td>
                                        <td><span class="text-muted">{{ $detail->part_number }}</span></td>
                                        <td><code class="text-primary font-monospace">{{ $detail->serial_number }}</code>
                                        </td>
                                        <td>
                                            @php
                                                $condClass = 'bg-success';
                                                if ($detail->condition == 'Used') {
                                                    $condClass = 'bg-warning text-dark';
                                                } elseif ($detail->condition == 'Defective') {
                                                    $condClass = 'bg-danger';
                                                }
                                            @endphp
                                            <span class="badge {{ $condClass }}">{{ $detail->condition }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span>MFG: {{ $detail->manufacture_date ?: '-' }}</span>
                                                <span>WAR: {{ $detail->warranty_end_date ?: '-' }}</span>
                                                <span>EOS: {{ $detail->eos_date ?: '-' }}</span>
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
