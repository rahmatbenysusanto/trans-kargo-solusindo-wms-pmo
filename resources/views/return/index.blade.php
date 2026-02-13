@extends('layout.index')
@section('title', 'Return To Client List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Return To Client List</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Return</a></li>
                        <li class="breadcrumb-item active">List</li>
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
                                <label class="form-label text-muted small text-uppercase">Return Date</label>
                                <input type="date" class="form-control border-light bg-light"
                                    value="{{ request()->get('delivery_date') }}" name="delivery_date">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Courier</label>
                                <input type="text" class="form-control border-light bg-light"
                                    value="{{ request()->get('courier') }}" name="courier" placeholder="Expedition ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">AWB / Tracking</label>
                                <input type="text" class="form-control border-light bg-light"
                                    value="{{ request()->get('awb') }}" name="awb" placeholder="Tracking No ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Received By</label>
                                <input type="text" class="form-control border-light bg-light"
                                    value="{{ request()->get('received_by') }}" name="received_by" placeholder="Name ...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-warning flex-grow-1 fw-bold">
                                    <i class="bx bx-search align-middle me-1"></i> Filter
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger">
                                    <i class="bx bx-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-warning border-3">
                <div class="card-header bg-light-subtle py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Return Transactions</h5>
                        <a href="{{ route('return.create') }}"
                            class="btn btn-warning btn-label waves-effect waves-light fw-bold">
                            <i class="bx bx-plus-circle label-icon align-middle fs-16 me-2"></i> Create Return
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Transaction No</th>
                                    <th>Client</th>
                                    <th>Site Location</th>
                                    <th class="text-center">Items</th>
                                    <th>Return Info</th>
                                    <th>Processed By</th>
                                    <th>Docs</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outbound as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $outbound->firstItem() + $index }}</td>
                                        <td>
                                            <span class="text-warning fw-medium">{{ $item->number }}</span>
                                            <small
                                                class="text-muted d-block">{{ $item->created_at->format('d M Y, H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $item->client->name }}</div>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="min-width: 150px;">
                                                <i class="bx bx-map-pin text-muted me-1"></i> {{ $item->site_location }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="avatar-sm mx-auto">
                                                <div
                                                    class="avatar-title bg-warning-subtle text-warning rounded-circle fw-bold">
                                                    {{ $item->qty }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0">
                                                    <i class="bx bx-calendar-event text-warning fs-18"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium">
                                                        {{ \Carbon\Carbon::parse($item->delivery_date)->format('d M Y') }}
                                                    </div>
                                                    <small class="text-muted">{{ $item->courier }} -
                                                        {{ $item->tracking_number }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-user-voice text-muted me-1"></i>
                                                <div>
                                                    <div class="small fw-medium">{{ $item->user->name }}</div>
                                                    <div class="small text-muted">Recipient: {{ $item->received_by }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('return.downloadExcel', ['id' => $item->id]) }}"
                                                    class="btn btn-soft-success btn-icon btn-sm" data-bs-toggle="tooltip"
                                                    title="Download Excel">
                                                    <i class="bx bxs-file-export"></i>
                                                </a>
                                                <a href="{{ route('return.downloadPDF', ['id' => $item->id]) }}"
                                                    class="btn btn-soft-danger btn-icon btn-sm" target="_blank"
                                                    data-bs-toggle="tooltip" title="Download PDF Document">
                                                    <i class="bx bxs-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('outbound.detail', ['id' => $item->id]) }}"
                                                class="btn btn-soft-secondary btn-sm">
                                                <i class="bx bx-show align-middle me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bx bx-undo fs-32 opacity-25 d-block mb-2"></i>
                                            No return transactions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $outbound->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
