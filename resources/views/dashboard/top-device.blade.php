@extends('layout.index')
@section('title', 'Dashboard Top Devices By Client')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Top Devices By Client</h4>
            </div>
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Client</label>
                                <select class="form-control" name="client">
                                    <option value="">-- All Client --</option>
                                    @foreach($client as $item)
                                        <option value="{{ $item->id }}" {{ request()->get('client') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label text-white">-</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
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
                                    <th class="text-center">Stock</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($inventory as $index => $item)
                                <tr>
                                    <td>{{ $inventory->firstItem() + $index }}</td>
                                    <td>{{ $item->part_name }}</td>
                                    <td>{{ $item->part_number }}</td>
                                    <td class="text-center fw-bold">{{ number_format($item->total_unit) }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm">Detail</a>
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
