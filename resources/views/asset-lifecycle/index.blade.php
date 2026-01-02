@extends('layout.index')
@section('title', 'Asset Lifecycle')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Asset Lifecycle</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a>Asset Lifecycle</a></li>
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
                                <label class="form-label">Lifecycle Status</label>
                                <select class="form-control" name="status">
                                    <option value="">-- Choose Status --</option>
                                    <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="near_eos" {{ request()->get('status') == 'near_eos' ? 'selected' : '' }}>Near EOS</option>
                                    <option value="eos" {{ request()->get('status') == 'eos' ? 'selected' : '' }}>EOS</option>
                                    <option value="unknown" {{ request()->get('status') == 'unknown' ? 'selected' : '' }}>Unknown</option>
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
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Manufacture Date</th>
                                    <th>Warranty End Date</th>
                                    <th>EOS Date</th>
                                    <th class="text-center">Lifecycle Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($inventory as $index => $item)
                                <tr>
                                    <td>{{ $inventory->firstItem() + $index }}</td>
                                    <td>{{ $item->part_name }}</td>
                                    <td>{{ $item->part_number }}</td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>{{ $item->manufacture_date ? \Carbon\Carbon::parse($item->manufacture_date)->translatedFormat('d F Y') : '' }}</td>
                                    <td>{{ $item->warranty_end_date ? \Carbon\Carbon::parse($item->warranty_end_date)->translatedFormat('d F Y') : '' }}</td>
                                    <td>{{ $item->eos_date ? \Carbon\Carbon::parse($item->eos_date)->translatedFormat('d F Y') : '' }}</td>
                                    <td class="text-center">
                                        @php
                                            if ($item->eos_date) {
                                                $eosDate = \Carbon\Carbon::parse($item->eos_date);
                                                $now = \Carbon\Carbon::now();

                                                if ($eosDate->isPast()) {
                                                    $status = 'EOS';
                                                    $class = 'badge bg-danger';
                                                } elseif ($eosDate->diffInMonths($now) <= 6) {
                                                    $status = 'Near EOS';
                                                    $class = 'badge bg-warning';
                                                } else {
                                                    $status = 'Active';
                                                    $class = 'badge bg-success';
                                                }
                                            } else {
                                                $status = 'Unknown';
                                                $class = 'badge bg-secondary';
                                            }
                                        @endphp
                                        <span class="{{ $class }}">{{ $status }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('assetLifecycle.detail', ['id' => $item->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        @if ($inventory->hasPages())
                            <ul class="pagination">
                                @if ($inventory->onFirstPage())
                                    <li class="disabled"><span>&laquo; Previous</span></li>
                                @else
                                    <li><a href="{{ $inventory->previousPageUrl() }}&per_page={{ request('per_page', 10) }}" rel="prev">&laquo; Previous</a></li>
                                @endif

                                @foreach ($inventory->links()->elements as $element)
                                    @if (is_string($element))
                                        <li class="disabled"><span>{{ $element }}</span></li>
                                    @endif

                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $inventory->currentPage())
                                                <li class="active"><span>{{ $page }}</span></li>
                                            @else
                                                <li><a href="{{ $url }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                @if ($inventory->hasMorePages())
                                    <li><a href="{{ $inventory->nextPageUrl() }}&per_page={{ request('per_page', 10) }}" rel="next">Next &raquo;</a></li>
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
