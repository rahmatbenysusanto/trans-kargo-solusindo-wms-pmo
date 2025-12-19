@extends('layout.index')
@section('title', 'Purchase Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Purchase Order</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item active">Purchase Order</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('inbound.purchaseOrder.create') }}" class="btn btn-primary mb-3">Create Purchase Order</a>
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
                                <input type="date" class="form-control" name="client" value="{{ request()->get('client') }}">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Ownership Status</label>
                                <input type="text" class="form-control" name="client" value="{{ request()->get('client') }}">
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
                                                <a href="{{ route('inbound.purchaseOrder.detail', ['number' => $item->number]) }}" class="btn btn-primary btn-sm">Detail</a>
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
                        url: '{{ route('inbound.purchaseOrder.changeStatus') }}',
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
                        url: '{{ route('inbound.purchaseOrder.changeStatus') }}',
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
