@extends('layout.index')
@section('title', 'Stock Movement')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Stock Movement</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Stock Movement</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('inventory.stockMovement.create') }}" class="btn btn-primary btn-sm">Change Storage Product</a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Part Name</label>
                                <input type="text" class="form-control" name="partName" value="{{ request()->get('partName', null) }}" placeholder="Part Name ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Part Number</label>
                                <input type="text" class="form-control" name="partNumber" value="{{ request()->get('partNumber', null) }}" placeholder="Part Number ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" name="serialNumber" value="{{ request()->get('serialNumber', null) }}" placeholder="Serial Number ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label text-white">-</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-secondary">Search</button>
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
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Old Location</th>
                                    <th>New Location</th>
                                    <th>Process By</th>
                                    <th>Process Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
