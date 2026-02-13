@extends('layout.index')
@section('title', 'Product History')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product History</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item">Inventory List</li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Inventory Information Header -->
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-8 border-end">
                            <div class="p-4">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="avatar-md bg-light rounded-circle d-flex align-items-center justify-content-center border border-light shadow-sm">
                                            <i class="ri-box-3-line fs-36 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="text-primary mb-1">{{ $inventory->part_name }}</h4>
                                        <div class="d-flex gap-3 text-muted">
                                            <span><i class="ri-barcode-line align-middle me-1"></i> PN: <span
                                                    class="text-dark fw-medium">{{ $inventory->part_number }}</span></span>
                                            <span><i class="ri-hashtag align-middle me-1"></i> SN: <code
                                                    class="text-primary fw-bold">{{ $inventory->serial_number }}</code></span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @php
                                            $statusClass = 'bg-success';
                                            if ($inventory->status == 'reserved') {
                                                $statusClass = 'bg-warning';
                                            } elseif ($inventory->status == 'defective') {
                                                $statusClass = 'bg-danger';
                                            } elseif ($inventory->status == 'in use') {
                                                $statusClass = 'bg-info';
                                            }
                                        @endphp
                                        <span
                                            class="badge {{ $statusClass }} fs-12 px-3 py-2 text-uppercase">{{ $inventory->status }}</span>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase fw-bold text-muted mb-3 small">Product Details</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Condition:</span>
                                                <span class="fw-medium text-dark">{{ $inventory->condition }}</span>
                                            </li>
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Manufacture Date:</span>
                                                <span
                                                    class="fw-medium text-dark">{{ $inventory->manufacture_date ?: '-' }}</span>
                                            </li>
                                            <li class="mb-2 d-flex justify-content-between">
                                                <span class="text-muted">Warranty End Date:</span>
                                                <span
                                                    class="fw-medium text-dark">{{ $inventory->warranty_end_date ?: '-' }}</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span class="text-muted">EOS Date:</span>
                                                <span class="fw-medium text-dark">{{ $inventory->eos_date ?: '-' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase fw-bold text-muted mb-3 small">Storage Location</h6>
                                        <div class="bg-light p-3 rounded">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ri-map-pin-2-fill text-danger me-2 fs-18"></i>
                                                <span class="fw-bold">{{ $inventory->bin->storageArea->name }}</span>
                                            </div>
                                            <div class="ps-4">
                                                <p class="text-muted mb-1 small">Rak: <span
                                                        class="text-dark fw-medium">{{ $inventory->bin->storageRak->name }}</span>
                                                </p>
                                                <p class="text-muted mb-1 small">Lantai: <span
                                                        class="text-dark fw-medium">{{ $inventory->bin->storageLantai->name }}</span>
                                                </p>
                                                <p class="text-muted mb-0 small">Bin: <span
                                                        class="text-dark fw-medium">{{ $inventory->bin->name }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 bg-light-subtle">
                            <div class="p-4 h-100">
                                <h6 class="text-uppercase fw-bold text-muted mb-3 small">Inbound Information</h6>
                                <div class="card border-0 mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 small uppercase">Reference Number</p>
                                                <h5 class="mb-0">{{ $inventory->inboundDetail->inbound->number }}</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="ri-file-list-3-line fs-24 text-info"></i>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <p class="text-muted mb-0 small">Client</p>
                                            <p class="fw-medium mb-0">
                                                {{ $inventory->inboundDetail->inbound->client->name }}</p>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 border-end">
                                                <p class="text-muted mb-0 small">Type</p>
                                                <span
                                                    class="badge bg-soft-info text-info">{{ $inventory->inboundDetail->inbound->inbound_type }}</span>
                                            </div>
                                            <div class="col-6 ps-3">
                                                <p class="text-muted mb-0 small">Date</p>
                                                <p class="fw-medium mb-0 small">
                                                    {{ \Carbon\Carbon::parse($inventory->inboundDetail->inbound->created_at)->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <p class="mb-1"><i class="ri-map-pin-line align-middle me-1"></i>
                                        {{ $inventory->inboundDetail->inbound->site_location }}</p>
                                    <p class="mb-0 text-capitalize"><i class="ri-user-star-line align-middle me-1"></i>
                                        Owner: {{ $inventory->inboundDetail->inbound->owner_status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="ri-history-line text-primary me-2 fs-20"></i> Product Movement History
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="ps-4">No</th>
                                    <th style="width: 150px;">Movement Type</th>
                                    <th>Description</th>
                                    <th style="width: 250px;">Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $item)
                                    <tr>
                                        <td class="ps-4 fw-medium text-muted">#{{ $loop->iteration }}</td>
                                        <td>
                                            @php
                                                $typeClass = 'bg-secondary';
                                                $typeIcon = 'ri-record-circle-line';

                                                if ($item->type == 'Inbound') {
                                                    $typeClass = 'bg-success';
                                                    $typeIcon = 'ri-arrow-right-down-line';
                                                } elseif ($item->type == 'Outbound') {
                                                    $typeClass = 'bg-danger';
                                                    $typeIcon = 'ri-arrow-left-up-line';
                                                } elseif ($item->type == 'Movement') {
                                                    $typeClass = 'bg-info';
                                                    $typeIcon = 'ri-arrow-left-right-line';
                                                }
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="badge {{ $typeClass }} px-3 py-1 d-flex align-items-center">
                                                    <i class="{{ $typeIcon }} me-1"></i> {{ $item->type }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-dark fw-medium">{{ $item->description }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ri-calendar-event-line text-muted me-2 fs-16"></i>
                                                <div>
                                                    <span
                                                        class="text-muted small d-block">{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</span>
                                                    <span
                                                        class="fw-medium">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="ri-database-2-line fs-40 opacity-25 d-block mb-3"></i>
                                            No movement history detected for this product.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
