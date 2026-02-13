@extends('layout.index')
@section('title', 'Stock Movement List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Stock Movement Log</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Movement History</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label text-muted small text-uppercase">Serial Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light text-muted"><i
                                            class="ri-search-2-line"></i></span>
                                    <input type="text" class="form-control border-light bg-light" name="serialNumber"
                                        value="{{ request()->get('serialNumber') }}"
                                        placeholder="Search by Serial Number ...">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="ri-filter-3-line align-bottom me-1"></i> Apply Filter
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger px-3">
                                    <i class="ri-refresh-line"></i>
                                </a>
                                <a href="{{ route('inventory.stockMovement.create') }}"
                                    class="btn btn-soft-info d-flex align-items-center px-4">
                                    <i class="ri-arrow-left-right-line me-1"></i> New Movement
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-history-line text-primary fs-20 me-2"></i>
                        <h5 class="card-title mb-0">Movement Audit Trail</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Asset Description</th>
                                    <th>Serial Number</th>
                                    <th>Movement Details</th>
                                    <th>Time Log</th>
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
                                                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                            <i class="ri-box-3-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 text-dark">{{ $item->inventory->part_name }}</h6>
                                                    <small class="text-muted">Client:
                                                        {{ $item->inventory->inboundDetail->inbound->client->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code
                                                class="text-primary font-monospace fw-medium">{{ $item->inventory->serial_number }}</code>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="max-width: 350px;">
                                                <span class="text-muted fw-medium d-block mb-1">DESCRIPTION:</span>
                                                <span class="fs-13 text-dark">{{ $item->description }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $item->created_at->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $item->created_at->format('H:i:s') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('inbound.inventory.history', ['id' => $item->inventory_id]) }}"
                                                class="btn btn-soft-secondary btn-icon btn-sm" data-bs-toggle="tooltip"
                                                title="Full History">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="ri-spam-2-line fs-32 opacity-25 d-block mb-2"></i>
                                            No movement history found for the selected filters.
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
                            Log entries: {{ $history->total() }} recorded movements
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
