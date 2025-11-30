@extends('layout.index')
@section('title', 'Put Away')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Put Away</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item active">Put Away</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
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
                                    <th>QTY Product</th>
                                    <th>QTY Item</th>
                                    <th>Type</th>
                                    <th>Received Date</th>
                                    <th>Received By</th>
                                    <th>Ownership Status</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
