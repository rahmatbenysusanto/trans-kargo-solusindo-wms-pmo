@extends('layout.index')
@section('title', 'Return To Client')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Return To Client</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Return</a></li>
                        <li class="breadcrumb-item active">Return To Client</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('return.create') }}" class="btn btn-primary">Create Return To Client</a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Number</label>
                                <input type="text" class="form-control" name="number" value="{{ request()->get('number', null) }}" placeholder="RTN-XXXXXX-XXXXX">
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
                                <label class="form-label">Return Date</label>
                                <input type="date" class="form-control" name="returnDate" value="{{ request()->get('returnDate', date('Y-m-d')) }}">
                            </div>
                            <div class="col-2">
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
                                    <th>Number</th>
                                    <th>Client</th>
                                    <th>Site Location</th>
                                    <th>QTY</th>
                                    <th>Return Date</th>
                                    <th>Process By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($outbound as $index => $item)
                                <tr>
                                    <td>{{ $outbound->firstItem() + $index }}</td>
                                    <td>{{ $item->number }}</td>
                                    <td>{{ $item->client->name }}</td>
                                    <td>{{ $item->site_location }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->delivery_date }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>
                                        <a class="btn btn-secondary btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        @if ($outbound->hasPages())
                            <ul class="pagination">
                                @if ($outbound->onFirstPage())
                                    <li class="disabled"><span>&laquo; Previous</span></li>
                                @else
                                    <li><a href="{{ $outbound->previousPageUrl() }}&per_page={{ request('per_page', 10) }}" rel="prev">&laquo; Previous</a></li>
                                @endif

                                @foreach ($outbound->links()->elements as $element)
                                    @if (is_string($element))
                                        <li class="disabled"><span>{{ $element }}</span></li>
                                    @endif

                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $outbound->currentPage())
                                                <li class="active"><span>{{ $page }}</span></li>
                                            @else
                                                <li><a href="{{ $url }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                @if ($outbound->hasMorePages())
                                    <li><a href="{{ $outbound->nextPageUrl() }}&per_page={{ request('per_page', 10) }}" rel="next">Next &raquo;</a></li>
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
@endsection
