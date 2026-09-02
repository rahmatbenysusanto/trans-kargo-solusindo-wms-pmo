@extends('layout.index')
@section('title', 'Back To WH List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Back To Warehouse List</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Back To WH</a></li>
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
                            <div class="col-md-3">
                                <label class="form-label text-muted small text-uppercase">Received Date</label>
                                <input type="date" class="form-control border-light bg-light"
                                    value="{{ request()->get('received_at') }}" name="received_at">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small text-uppercase">Received By</label>
                                <input type="text" class="form-control border-light bg-light"
                                    value="{{ request()->get('received_by') }}" name="received_by" placeholder="Recipient Name ...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small text-uppercase">Reason</label>
                                <input type="text" class="form-control border-light bg-light"
                                    value="{{ request()->get('reason') }}" name="reason" placeholder="Search reason ...">
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                    <i class="bx bx-search align-middle me-1"></i> Filter
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger">
                                    <i class="bx bx-refresh"></i>
                                </a>
                            </div>
                        </div>

                        @include('components.serial-number-multi-search')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Back To Warehouse Transactions</h5>
                        <a href="{{ route('backToWh.create') }}"
                            class="btn btn-primary btn-label waves-effect waves-light fw-bold">
                            <i class="bx bx-plus-circle label-icon align-middle fs-16 me-2"></i> Create Back To WH
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
                                    <th>Received Date</th>
                                    <th class="text-center">Items</th>
                                    <th>Reason</th>
                                    <th>Received By</th>
                                    <th>Created By</th>
                                    <th>Docs</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backToWh as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $backToWh->firstItem() + $index }}</td>
                                        <td>
                                            <span class="text-primary fw-medium">{{ $item->number }}</span>
                                            <small
                                                class="text-muted d-block">{{ $item->created_at->format('d M Y, H:i') }}</small>
                                        </td>
                                        <td>
                                            {{ $item->received_at ? \Carbon\Carbon::parse($item->received_at)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="avatar-sm mx-auto">
                                                <div class="avatar-title bg-info-subtle text-info rounded-circle fw-bold">
                                                    {{ $item->details_count ?? $item->details->count() }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-wrap" style="min-width: 200px;">
                                                {{ Str::limit($item->reason, 80) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $item->received_by ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="small fw-medium">{{ $item->user->name ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('backToWh.downloadExcel', ['id' => $item->id]) }}"
                                                    class="btn btn-soft-success btn-icon btn-sm" data-bs-toggle="tooltip"
                                                    title="Download Excel">
                                                    <i class="bx bxs-file-export"></i>
                                                </a>
                                                <a href="{{ route('backToWh.downloadPDF', ['id' => $item->id]) }}"
                                                    class="btn btn-soft-danger btn-icon btn-sm" target="_blank"
                                                    data-bs-toggle="tooltip" title="Download PDF Document">
                                                    <i class="bx bxs-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('backToWh.detail', ['id' => $item->id]) }}"
                                                class="btn btn-soft-secondary btn-sm">
                                                <i class="bx bx-show align-middle me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bx bx-archive-in fs-32 opacity-25 d-block mb-2"></i>
                                            No back to warehouse transactions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $backToWh->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
