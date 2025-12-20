@extends('layout.index')
@section('title', 'Inventory List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Inventory List</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Inventory List</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Part Name</label>
                                <input type="text" class="form-control" name="partName" value="{{ request()->get('partName') }}" placeholder="Part Name ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Part Number</label>
                                <input type="text" class="form-control" name="partNumber" value="{{ request()->get('partNumber') }}" placeholder="Part Number ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" name="serialNumber" value="{{ request()->get('serialNumber') }}" placeholder="Serial Number ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Client</label>
                                <select class="form-control" name="client">
                                    <option value="">-- Choose Client --</option>
                                    @foreach($client as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">-- Choose Status --</option>
                                    <option value="available">Available</option>
                                    <option value="reserved">Reserved</option>
                                    <option value="in use">In Use</option>
                                    <option value="defective">Defective</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label text-white">-</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary">Search</button>
                                    <a href="{{ url()->current() }}" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Storage</th>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Owner Status</th>
                                    <th>PIC</th>
                                    <th class="text-center">Status</th>
                                    <th>Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($inventory as $index => $product)
                                <tr>
                                    <td>{{ $inventory->firstItem() + $index }}</td>
                                    <td>{{ $product->bin->storageArea->name }} - {{ $product->bin->storageRak->name }} - {{ $product->bin->storageLantai->name }} - {{ $product->bin->name }}</td>
                                    <td>{{ $product->part_name }}</td>
                                    <td>{{ $product->part_number }}</td>
                                    <td>{{ $product->serial_number }}</td>
                                    <td>{{ $product->inboundDetail->inbound->owner_status }}</td>
                                    <td>{{ $product->pic }}</td>
                                    <td class="text-center">
                                        @switch($product->status)
                                            @case('available')
                                                <span class="badge bg-success">Available</span>
                                                @break
                                            @case('reserved')
                                                <span class="badge bg-warning">Reserved</span>
                                                @break
                                            @case('in use')
                                                <span class="badge bg-secondary">In Use</span>
                                                @break
                                            @case('defective')
                                                <span class="badge bg-danger">Defective</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $product->inboundDetail->inbound->client->name }}</td>
                                    <td>
                                        <a href="{{ route('inbound.inventory.history', ['id' => $product->id]) }}" class="btn btn-secondary btn-sm">History</a>
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
@endsection
