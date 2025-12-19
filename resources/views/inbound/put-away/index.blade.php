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
                                                <a class="btn btn-primary btn-sm">Detail</a>
                                                @if($item->status == 'open')
                                                    <a href="{{ route('inbound.putAway.process', ['number' => $item->number]) }}" class="btn btn-secondary btn-sm">Process Put Away</a>
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
