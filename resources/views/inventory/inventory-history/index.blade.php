@extends('layout.index')
@section('title', 'Inventory History')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Inventory History</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small text-uppercase fw-bold">Serial Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light"><i class="bx bx-barcode-reader"></i></span>
                                    <input type="text" class="form-control border-light bg-light" name="serialNumber"
                                        value="{{ request()->get('serialNumber') }}" placeholder="Search by Serial Number ...">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                    <i class="bx bx-search align-middle me-1"></i> Search
                                </button>
                                <a href="{{ route('inventory.inventoryHistory') }}" class="btn btn-soft-danger px-3">
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
                <div class="card-header bg-light-subtle py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Outbounded Products History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Product Details</th>
                                    <th>Serial Number</th>
                                    <th>Previous Storage</th>
                                    <th>Outbound Reference</th>
                                    <th>Client</th>
                                    <th>Outbound Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $history->firstItem() + $index }}</td>
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
                                                    <h6 class="fs-14 mb-0 text-dark">{{ $item->inventory->part_name }}</h6>
                                                    <small class="text-muted">PN: {{ $item->inventory->part_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary font-monospace fw-medium">{{ $item->inventory->serial_number }}</code>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-map-pin text-muted me-2"></i>
                                                <div>
                                                    <div class="small fw-medium">{{ $item->inventory->bin->storageArea->name }}</div>
                                                    <div class="text-muted" style="font-size: 11px;">
                                                        {{ $item->inventory->bin->storageRak->name }} •
                                                        {{ $item->inventory->bin->storageLantai->name }} •
                                                        {{ $item->inventory->bin->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-export text-warning me-2 fs-18"></i>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $item->outbound->number }}</div>
                                                    <small class="text-muted">Type: {{ ucfirst($item->outbound->type) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $item->outbound->client->name }}</span>
                                        </td>
                                        <td>
                                            <div class="small fw-medium">{{ $item->outbound->delivery_date }}</div>
                                            <small class="text-muted">{{ $item->outbound->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('outbound.detail', ['id' => $item->outbound->id]) }}"
                                                class="btn btn-soft-primary btn-icon btn-sm waves-effect waves-light"
                                                data-bs-toggle="tooltip" title="View Outbound Detail">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('inbound.inventory.history', ['id' => $item->inventory->id]) }}"
                                                class="btn btn-soft-secondary btn-icon btn-sm waves-effect waves-light"
                                                data-bs-toggle="tooltip" title="View Life History">
                                                <i class="bx bx-history"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-muted display-5 rounded-circle">
                                                    <i class="bx bx-history"></i>
                                                </div>
                                            </div>
                                            <h5>No history found</h5>
                                            <p class="text-muted">No outbounded products match your search criteria.</p>
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
                            Showing {{ $history->firstItem() ?? 0 }} to {{ $history->lastItem() ?? 0 }} of
                            {{ $history->total() }} entries
                        </div>
                        <div>
                            {{ $history->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
