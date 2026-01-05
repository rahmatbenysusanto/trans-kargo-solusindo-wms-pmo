@extends('layout.index')
@section('title', 'Receiving')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Receiving</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item active">Receiving</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('inbound.receiving.create') }}" class="btn btn-primary mb-3">Create Receiving</a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Number</label>
                                <input type="text" class="form-control" name="number" value="{{ request()->get('number') }}" placeholder="Number ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Client</label>
                                <input type="text" class="form-control" name="client" value="{{ request()->get('client') }}" placeholder="Client ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Received Date</label>
                                <input type="date" class="form-control" name="received" value="{{ request()->get('received') }}">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Ownership Status</label>
                                <select class="form-control" name="ownership">
                                    <option value="">-- Choose Ownership Status --</option>
                                    <option {{ request()->get('ownership') == 'Milik Client' ? 'selected' : ''}}>Milik Client</option>
                                    <option {{ request()->get('ownership') == 'Titipan' ? 'selected' : ''}}>Titipan</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Inbound Type</label>
                                <select class="form-control" name="inbound_type">
                                    <option value="">-- Choose Inbound Type --</option>
                                    <option {{ request()->get('inbound_type') == 'Dismantle' ? 'selected' : ''}}>Dismantle</option>
                                    <option {{ request()->get('inbound_type') == 'Relocation' ? 'selected' : ''}}>Relocation</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label text-white">-</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url()->current() }}" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Number</th>
                                        <th>Client</th>
                                        <th class="text-center">QTY</th>
                                        <th>Type</th>
                                        <th>Received Date</th>
                                        <th>Received By</th>
                                        <th>Ownership Status</th>
                                        <th class="text-center">Status</th>
                                        <th>Doc</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($inbound as $index => $item)
                                    <tr>
                                        <td>{{ $inbound->firstItem() + $index }}</td>
                                        <td>{{ $item->number }}</td>
                                        <td>{{ $item->client->name }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td>{{ $item->inbound_type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->owner_status }}</td>
                                        <td class="text-center">
                                            @if($item->status == 'new')
                                                <span class="badge bg-secondary">New</span>
                                            @elseif($item->status == 'open')
                                                <span class="badge bg-primary">Open</span>
                                            @elseif($item->status == 'cancel')
                                                <span class="badge bg-danger">Cancel</span>
                                            @elseif($item->status == 'close')
                                                <span class="badge bg-success">Close</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('inbound.receiving.downloadExcel', ['id' => $item->id]) }}" class="btn btn-success btn-sm">
                                                    <i class="mdi mdi-file-excel" style="font-size: 14px;"></i>
                                                </a>
                                                <a href="{{ route('inbound.receiving.downloadPDF', ['id' => $item->id]) }}" class="btn btn-pdf btn-sm text-white" target="_blank">
                                                    <i class="mdi mdi-file-pdf-box" style="font-size: 14px;"></i>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('inbound.receiving.detail', ['number' => $item->number]) }}" class="btn btn-primary btn-sm">Detail</a>
                                                @if($item->status == 'new')
                                                    <a class="btn btn-secondary btn-sm" onclick="processPO('{{ $item->number }}')">Process PO</a>
                                                    <a class="btn btn-danger btn-sm" onclick="cancelPO('{{ $item->number }}')">Cancel PO</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            @if ($inbound->hasPages())
                                <ul class="pagination">
                                    @if ($inbound->onFirstPage())
                                        <li class="disabled"><span>&laquo; Previous</span></li>
                                    @else
                                        <li><a href="{{ $inbound->previousPageUrl() }}&per_page={{ request('per_page', 10) }}" rel="prev">&laquo; Previous</a></li>
                                    @endif

                                    @foreach ($inbound->links()->elements as $element)
                                        @if (is_string($element))
                                            <li class="disabled"><span>{{ $element }}</span></li>
                                        @endif

                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                @if ($page == $inbound->currentPage())
                                                    <li class="active"><span>{{ $page }}</span></li>
                                                @else
                                                    <li><a href="{{ $url }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach

                                    @if ($inbound->hasMorePages())
                                        <li><a href="{{ $inbound->nextPageUrl() }}&per_page={{ request('per_page', 10) }}" rel="next">Next &raquo;</a></li>
                                    @else
                                        <li class="disabled"><span>Next &raquo;</span></li>
                                    @endif
                                </ul>
                            @endif

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
                title: "Are you sure?",
                text: `Process PO ${number}`,
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Process PO!",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t)=> {
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
                                title: 'Success',
                                text: 'Approved PO Successfully',
                                icon: 'success'
                            }).then((i) => {
                                window.location.reload();
                            });
                        }
                    });

                }
            });
        }

        function cancelPO(number) {
            Swal.fire({
                title: "Are you sure?",
                text: `Cancel PO ${number}`,
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Cancel PO!",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t)=> {
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
                                title: 'Success',
                                text: 'Cancel PO Successfully',
                                icon: 'success'
                            }).then((i) => {
                                window.location.reload();
                            });
                        }
                    });

                }
            });
        }
    </script>
@endsection
