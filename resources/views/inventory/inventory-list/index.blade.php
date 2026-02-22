@extends('layout.index')
@section('title', 'Inventory List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Inventory Stock</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Stock List</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Part Name</label>
                                <input type="text" class="form-control border-light bg-light" name="partName"
                                    value="{{ request()->get('partName') }}" placeholder="Search Name ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Part Number</label>
                                <input type="text" class="form-control border-light bg-light" name="partNumber"
                                    value="{{ request()->get('partNumber') }}" placeholder="Search PN ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Serial Number</label>
                                <input type="text" class="form-control border-light bg-light" name="serialNumber"
                                    value="{{ request()->get('serialNumber') }}" placeholder="Search SN ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Client</label>
                                <select class="form-select border-light bg-light" name="client">
                                    <option value="">All Clients</option>
                                    @foreach ($client as $item)
                                        <option value="{{ $item->id }}"
                                            {{ request()->get('client') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Stock Status</label>
                                <select class="form-select border-light bg-light" name="status">
                                    <option value="">All Status</option>
                                    <option value="available"
                                        {{ request()->get('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="reserved" {{ request()->get('status') == 'reserved' ? 'selected' : '' }}>
                                        Reserved</option>
                                    <option value="in use" {{ request()->get('status') == 'in use' ? 'selected' : '' }}>In
                                        Use</option>
                                    <option value="defective"
                                        {{ request()->get('status') == 'defective' ? 'selected' : '' }}>Defective</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                    <i class="bx bx-search align-middle me-1"></i> Search
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger px-3">
                                    <i class="bx bx-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div
                    class="card-header bg-light-subtle py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Live Inventory Records</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('inventory.downloadExcel', request()->all()) }}"
                            class="btn btn-soft-success btn-sm btn-label waves-effect waves-light fw-bold">
                            <i class="bx bxs-file-export label-icon align-middle fs-16 me-2"></i> Export Excel
                        </a>
                        <a href="{{ route('inventory.downloadPDF', request()->all()) }}" target="_blank"
                            class="btn btn-soft-danger btn-sm btn-label waves-effect waves-light fw-bold">
                            <i class="bx bxs-file-pdf label-icon align-middle fs-16 me-2"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Asset Details</th>
                                    <th>Storage Location</th>
                                    <th>Serial Number</th>
                                    <th>Ownership</th>
                                    <th class="text-center">Status</th>
                                    <th>Client / Owner</th>
                                    <th>Remark</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $index => $product)
                                    <tr>
                                        <td class="text-muted">{{ $inventory->firstItem() + $index }}</td>
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
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-map-pin text-muted me-2"></i>
                                                <div>
                                                    <div class="small fw-medium">{{ $product->bin->storageArea->name }}
                                                    </div>
                                                    <div class="text-muted" style="font-size: 11px;">
                                                        {{ $product->bin->storageRak->name }} •
                                                        {{ $product->bin->storageLantai->name }} •
                                                        {{ $product->bin->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code
                                                class="text-primary font-monospace fw-medium">{{ $product->serial_number }}</code>
                                        </td>
                                        <td>
                                            <div class="small fw-medium">
                                                {{ $product->inboundDetail->inbound->owner_status }}</div>
                                            <div class="text-muted" style="font-size: 11px;">PIC:
                                                {{ $product->pic->name ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = 'bg-secondary';
                                                $statusText = ucfirst($product->status);
                                                switch ($product->status) {
                                                    case 'available':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'reserved':
                                                        $statusClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'in use':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'defective':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }} px-3 py-2 fs-11 text-uppercase">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border">{{ $product->inboundDetail->inbound->client->name }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $product->remark ?: '-' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('inbound.inventory.history', ['id' => $product->id]) }}"
                                                class="btn btn-soft-secondary btn-icon btn-sm waves-effect waves-light"
                                                data-bs-toggle="tooltip" title="View History">
                                                <i class="bx bx-history"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="bx bx-box fs-32 opacity-25 d-block mb-2"></i>
                                            No assets found matching the search criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted small">
                            Showing {{ $inventory->firstItem() ?? 0 }} to {{ $inventory->lastItem() ?? 0 }} of
                            {{ $inventory->total() }} entries
                        </div>
                        <div>
                            {{ $inventory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
