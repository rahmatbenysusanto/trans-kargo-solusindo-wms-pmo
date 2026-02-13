@extends('layout.index')
@section('title', 'Put Away List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Put Away Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item active">Put Away</li>
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
                                <label class="form-label text-muted small text-uppercase">Inbound No</label>
                                <input type="text" class="form-control border-light bg-light" name="number"
                                    value="{{ request()->get('number') }}" placeholder="Search No ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Client</label>
                                <input type="text" class="form-control border-light bg-light" name="client"
                                    value="{{ request()->get('client') }}" placeholder="Client Name ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Date Received</label>
                                <input type="date" class="form-control border-light bg-light" name="received"
                                    value="{{ request()->get('received') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Ownership</label>
                                <select class="form-select border-light bg-light" name="ownership">
                                    <option value="">All Status</option>
                                    <option {{ request()->get('ownership') == 'Milik Client' ? 'selected' : '' }}>Milik
                                        Client</option>
                                    <option {{ request()->get('ownership') == 'Titipan' ? 'selected' : '' }}>Titipan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Type</label>
                                <select class="form-select border-light bg-light" name="inbound_type">
                                    <option value="">All Types</option>
                                    <option {{ request()->get('inbound_type') == 'Dismantle' ? 'selected' : '' }}>Dismantle
                                    </option>
                                    <option {{ request()->get('inbound_type') == 'Relocation' ? 'selected' : '' }}>
                                        Relocation</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="ri-search-line align-bottom me-1"></i> Filter
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger px-3">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-info border-3">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Pending Put Away Tasks</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Transaction Details</th>
                                    <th>Client / Ownership</th>
                                    <th class="text-center">Items</th>
                                    <th>Inbound Type</th>
                                    <th>Received Info</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inbound as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $inbound->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-semibold text-primary mb-1">{{ $item->number }}</div>
                                            <small class="text-muted"><i class="ri-user-2-line me-1"></i> Received by:
                                                {{ $item->user->name }}</small>
                                        </td>
                                        <td>
                                            <div class="text-dark fw-medium">{{ $item->client->name }}</div>
                                            <div class="badge badge-soft-info">{{ $item->owner_status }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="avatar-sm mx-auto">
                                                <div class="avatar-title bg-info-subtle text-info rounded-circle fw-bold">
                                                    {{ $item->quantity }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-medium">{{ $item->inbound_type }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</small>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = 'bg-secondary';
                                                switch ($item->status) {
                                                    case 'new':
                                                        $statusClass = 'bg-secondary';
                                                        break;
                                                    case 'open':
                                                        $statusClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'close':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'cancel':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }} text-uppercase px-3 py-2">{{ $item->status }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('inbound.putAway.detail', ['number' => $item->number]) }}"
                                                    class="btn btn-soft-secondary btn-sm" data-bs-toggle="tooltip"
                                                    title="View Detail">
                                                    <i class="ri-eye-line align-bottom me-1"></i> Detail
                                                </a>
                                                @if ($item->status == 'open')
                                                    <a href="{{ route('inbound.putAway.process', ['number' => $item->number]) }}"
                                                        class="btn btn-success btn-sm btn-label waves-effect waves-light">
                                                        <i class="ri-drag-drop-line label-icon align-middle fs-16 me-2"></i>
                                                        Put Away
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="ri-inbox-line fs-32 opacity-25 d-block mb-2"></i>
                                            No pending put away tasks found.
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
                            Transactions: {{ $inbound->total() }} recorded
                        </div>
                        <div>
                            {{ $inbound->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
