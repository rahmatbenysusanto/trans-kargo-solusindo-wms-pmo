@extends('layout.index')
@section('title', 'Storage Inventory Detail')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Storage Inventory Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory.storageInventory.index') }}">Storage Inventory</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        @if($inventory->isNotEmpty())
        <div class="col-12 mb-3">
            <div class="card shadow-sm border-0 border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded fs-24">
                                    <i class="bx bx-store"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="fs-16 mb-1 text-dark">Location: {{ $inventory->first()->bin->storageArea->name }}</h5>
                            <p class="text-muted mb-0">
                                {{ $inventory->first()->bin->storageRak->name }} •
                                {{ $inventory->first()->bin->storageLantai->name }} •
                                {{ $inventory->first()->bin->name }}
                            </p>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('inventory.storageInventory.index') }}" class="btn btn-light btn-sm px-3">
                                <i class="bx bx-arrow-back align-middle me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-light-subtle py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detailed Item Records</h5>
                    <div class="d-flex gap-2">
                         <span class="badge bg-soft-primary text-primary px-3 py-2 fs-12 fw-bold text-uppercase border">
                            TOTAL: {{ number_format($inventory->sum('qty')) }} Unit
                         </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Asset Details</th>
                                    <th>Serial Number</th>
                                    <th>Client / Owner</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Status</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $index => $product)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-info-subtle text-info rounded">
                                                            <i class="bx bx-package"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 text-dark">{{ $product->part_name }}</h6>
                                                    <small class="text-muted">PN: {{ $product->part_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary font-monospace fw-medium fs-13">{{ $product->serial_number }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1">{{ $product->client->name }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ number_format($product->qty) }}</td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = 'bg-secondary';
                                                $statusText = ucfirst($product->status);
                                                switch ($product->status) {
                                                    case 'available': $statusClass = 'bg-success'; break;
                                                    case 'reserved': $statusClass = 'bg-warning text-dark'; break;
                                                    case 'in use': $statusClass = 'bg-info'; break;
                                                    case 'defective': $statusClass = 'bg-danger'; break;
                                                }
                                            @endphp
                                            <span class="badge {{ $statusClass }} px-3 py-2 fs-11 text-uppercase">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $product->remark ?: '-' }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bx bx-box fs-32 opacity-25 d-block mb-2"></i>
                                            No assets found in this storage location.
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
