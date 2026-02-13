@extends('layout.index')
@section('title', 'Asset Lifecycle')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Asset Lifecycle Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Asset Lifecycle</li>
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
                                    value="{{ request()->get('partName') }}" placeholder="Part Name ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Part Number</label>
                                <input type="text" class="form-control border-light bg-light" name="partNumber"
                                    value="{{ request()->get('partNumber') }}" placeholder="Part Number ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Serial Number</label>
                                <input type="text" class="form-control border-light bg-light" name="serialNumber"
                                    value="{{ request()->get('serialNumber') }}" placeholder="Serial Number ...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small text-uppercase">Lifecycle Status</label>
                                <select class="form-select border-light bg-light" name="lifecycleStatus">
                                    <option value="">All Statuses</option>
                                    <option value="Active"
                                        {{ request()->get('lifecycleStatus') == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Near EOS"
                                        {{ request()->get('lifecycleStatus') == 'Near EOS' ? 'selected' : '' }}>Near EOS
                                    </option>
                                    <option value="EOS"
                                        {{ request()->get('lifecycleStatus') == 'EOS' ? 'selected' : '' }}>EOS</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="ri-search-line align-bottom me-1"></i> Search
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger px-3">
                                    <i class="ri-refresh-line"></i>
                                </a>
                                <a href="{{ route('assetLifecycle.massEdit') }}"
                                    class="btn btn-soft-info d-flex align-items-center px-3">
                                    <i class="ri-edit-2-line me-1"></i> Mass Edit
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
                    <h5 class="card-title mb-0">Inventory Assets Lifecycle Status</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Asset Details</th>
                                    <th>Lifecycle Timeline</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $inventory->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-soft-info text-info rounded-circle">
                                                            <i class="ri-box-3-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 text-dark">{{ $item->part_name }}</h6>
                                                    <small class="text-muted">PN: {{ $item->part_number }} | SN: <span
                                                            class="text-primary font-monospace">{{ $item->serial_number }}</span></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="small"><span class="text-muted fw-medium">MFG:</span>
                                                    {{ $item->manufacture_date ? \Carbon\Carbon::parse($item->manufacture_date)->format('d M Y') : '-' }}
                                                </div>
                                                <div class="small"><span class="text-muted fw-medium">WARRANTY:</span>
                                                    {{ $item->warranty_end_date ? \Carbon\Carbon::parse($item->warranty_end_date)->format('d M Y') : '-' }}
                                                </div>
                                                <div class="small"><span class="text-muted fw-medium">EOS:</span>
                                                    {{ $item->eos_date ? \Carbon\Carbon::parse($item->eos_date)->format('d M Y') : '-' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $status = 'Unknown';
                                                $class = 'bg-secondary';

                                                if ($item->eos_date) {
                                                    $eosDate = \Carbon\Carbon::parse($item->eos_date);
                                                    $now = \Carbon\Carbon::now();

                                                    if ($eosDate->isPast()) {
                                                        $status = 'EOS';
                                                        $class = 'bg-danger';
                                                    } elseif ($eosDate->diffInMonths($now) <= 6) {
                                                        $status = 'Near EOS';
                                                        $class = 'bg-warning text-dark';
                                                    } else {
                                                        $status = 'Active';
                                                        $class = 'bg-success';
                                                    }
                                                }
                                            @endphp
                                            <span class="badge {{ $class }} px-3 py-2 fs-11 text-uppercase"
                                                style="min-width: 80px;">{{ $status }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('assetLifecycle.detail', ['id' => $item->id]) }}"
                                                class="btn btn-soft-primary btn-sm btn-icon waves-effect waves-light"
                                                data-bs-toggle="tooltip" title="Edit Lifecycle">
                                                <i class="ri-edit-box-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="ri-history-line fs-32 opacity-25 d-block mb-2"></i>
                                            No assets found for lifecycle tracking.
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
                            Assets: {{ $inventory->total() }} tracked items
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
