@extends('layout.index')
@section('title', 'Receiving Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Inbound Receiving</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item active">Receiving Log</li>
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
                    <h5 class="card-title mb-0">Incoming Shipment Records</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('inbound.receiving.bulkImport') }}"
                            class="btn btn-soft-success btn-label waves-effect waves-light btn-sm fw-bold">
                            <i class="bx bx-file label-icon align-middle fs-16 me-2"></i> Bulk Import Excel
                        </a>
                        <a href="{{ route('inbound.receiving.create') }}"
                            class="btn btn-primary btn-label waves-effect waves-light btn-sm fw-bold">
                            <i class="bx bx-plus-circle label-icon align-middle fs-16 me-2"></i> Create New Receiving
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Transaction No</th>
                                    <th>PIC</th>
                                    <th>Client / Status</th>
                                    <th class="text-center">QTY</th>
                                    <th>Inbound Type</th>
                                    <th>Received Info</th>
                                    <th class="text-center">Status</th>
                                    <th>Attachments</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inbound as $index => $item)
                                    <tr>
                                        <td class="text-muted">{{ $inbound->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-semibold text-primary mb-1">{{ $item->number }}</div>
                                            <div class="badge bg-info-subtle text-info border border-info-subtle fs-11">
                                                {{ $item->owner_status }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $item->pic->name ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="text-dark fw-medium">{{ $item->client->name }}</div>
                                            <small class="text-muted"><i class="bx bx-user me-1"></i>
                                                {{ $item->user->name }}</small>
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
                                        <td>
                                            <div class="d-flex gap-1 justify-content-start">
                                                <a href="{{ route('inbound.receiving.downloadExcel', ['id' => $item->id]) }}"
                                                    class="btn btn-soft-success btn-icon btn-sm" data-bs-toggle="tooltip"
                                                    title="Download Excel">
                                                    <i class="bx bxs-file-export"></i>
                                                </a>
                                                <a href="{{ route('inbound.receiving.downloadPDF', ['id' => $item->id]) }}"
                                                    target="_blank" class="btn btn-soft-danger btn-icon btn-sm"
                                                    data-bs-toggle="tooltip" title="Download PDF">
                                                    <i class="bx bxs-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-soft-secondary btn-sm btn-icon waves-effect waves-light"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('inbound.receiving.detail', ['number' => $item->number]) }}"><i
                                                                class="bx bx-show align-middle me-2 text-muted"></i> View
                                                            Detail</a></li>
                                                    @if ($item->status == 'new')
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item text-primary"
                                                                href="javascript:void(0);"
                                                                onclick="processPO('{{ $item->number }}')"><i
                                                                    class="bx bx-check-circle align-middle me-2"></i>
                                                                Process
                                                                PO</a></li>
                                                        <li><a class="dropdown-item text-danger"
                                                                href="javascript:void(0);"
                                                                onclick="cancelPO('{{ $item->number }}')"><i
                                                                    class="bx bx-x-circle align-middle me-2"></i>
                                                                Cancel PO</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bx bx-archive fs-32 opacity-25 d-block mb-2"></i>
                                            No receiving records found for the selected criteria.
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
                            Total Records: {{ $inbound->total() }} entries
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

@section('js')
    <script>
        function processPO(number) {
            Swal.fire({
                title: "Approve Receiving?",
                text: `You are about to approve and process Inbound No: ${number}. This will allow the items to be put away.`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Approve Now",
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-light w-xs mt-2"
                },
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t) => {
                if (t.value) {
                    $.ajax({
                        url: '{{ route('inbound.receiving.changeStatus') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            number: number,
                            status: 'open'
                        },
                        success: (res) => {
                            Swal.fire({
                                title: 'Approved!',
                                text: 'Transaction has been moved to Open status.',
                                icon: 'success'
                            }).then(() => window.location.reload());
                        }
                    });
                }
            });
        }

        function cancelPO(number) {
            Swal.fire({
                title: "Cancel Transaction?",
                text: `Are you sure you want to cancel Inbound No: ${number}? This action cannot be undone.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Cancel It",
                customClass: {
                    confirmButton: "btn btn-danger w-xs me-2 mt-2",
                    cancelButton: "btn btn-light w-xs mt-2"
                },
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t) => {
                if (t.value) {
                    $.ajax({
                        url: '{{ route('inbound.receiving.changeStatus') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            number: number,
                            status: 'cancel'
                        },
                        success: (res) => {
                            Swal.fire({
                                title: 'Cancelled',
                                text: 'Transaction status updated to Cancel.',
                                icon: 'success'
                            }).then(() => window.location.reload());
                        }
                    });
                }
            });
        }
    </script>
@endsection
