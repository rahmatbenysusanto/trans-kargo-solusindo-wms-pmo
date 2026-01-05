@extends('layout.index')
@section('title', 'Outbound')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Outbound</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a>Outbound</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3">
            <a href="{{ route('outbound.create') }}" class="btn btn-secondary">Create Outbound</a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form>
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Client</label>
                                <select class="form-control" name="client">
                                    <option value="">-- Choose Client --</option>
                                    @foreach($client as $item)
                                        <option value="{{ $item->id }}" {{ request()->get('client') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Delivery Date</label>
                                <input type="date" class="form-control" value="{{ request()->get('delivery_date') }}" name="delivery_date">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Courier</label>
                                <input type="text" class="form-control" value="{{ request()->get('courier') }}" name="courier" placeholder="Courier ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">AWB</label>
                                <input type="text" class="form-control" value="{{ request()->get('awb') }}" name="awb" placeholder="AWB ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Received By</label>
                                <input type="text" class="form-control" value="{{ request()->get('received_by') }}" name="awb" placeholder="Received By ...">
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
                        <table class="table table striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Number</th>
                                    <th>Client Name</th>
                                    <th>Site Location</th>
                                    <th class="text-center">QTY</th>
                                    <th>Delivery Date</th>
                                    <th>Courier</th>
                                    <th>AWB</th>
                                    <th>Received By</th>
                                    <th>Processed By</th>
                                    <th>Doc</th>
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
                                    <td class="text-center fw-bold">{{ number_format($item->qty) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->delivery_date)->translatedFormat('d F Y') }}</td>
                                    <td>{{ $item->courier }}</td>
                                    <td>{{ $item->tracking_number }}</td>
                                    <td>{{ $item->received_by }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('outbound.downloadExcel', ['id' => $item->id]) }}" class="btn btn-success btn-sm">
                                                <i class="mdi mdi-file-excel" style="font-size: 14px;"></i>
                                            </a>
                                            <a href="{{ route('outbound.downloadPDF', ['id' => $item->id]) }}" class="btn btn-pdf btn-sm text-white" target="_blank">
                                                <i class="mdi mdi-file-pdf-box" style="font-size: 14px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('outbound.detail', ['id' => $item->id]) }}" class="btn btn-secondary btn-sm">Detail</a>
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
