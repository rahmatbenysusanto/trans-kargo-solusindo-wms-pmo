@extends('layout.index')
@section('title', 'Top Devices Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">High-Demand inventory</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Dashboard</a></li>
                        <li class="breadcrumb-item active">Top Devices</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-4">
                    <form action="" method="GET">
                        <div class="row align-items-end g-3">
                            <div class="col-md-5">
                                <label class="form-label text-muted small text-uppercase fw-bold">Select Client
                                    Portfolio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="mdi mdi-account-search-outline"></i></span>
                                    <select class="form-select border-light bg-light fw-medium" name="client">
                                        <option value="">-- View All Clients Portfolio --</option>
                                        @foreach ($client as $item)
                                            <option value="{{ $item->id }}"
                                                {{ request()->get('client') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                    <i class="mdi mdi-filter-variant align-middle me-1"></i> Apply Filter
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger px-3 shadow-sm">
                                    <i class="mdi mdi-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="ps-4">Rank</th>
                                    <th>Device Identification</th>
                                    <th>Part Number</th>
                                    <th class="text-center">Available Stock</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $index => $item)
                                    <tr>
                                        <td class="ps-4">
                                            @if ($inventory->firstItem() + $index == 1)
                                                <span class="badge bg-warning fs-13"><i class="mdi mdi-medal me-1"></i>
                                                    1st</span>
                                            @elseif($inventory->firstItem() + $index == 2)
                                                <span class="badge bg-silver text-dark bg-secondary bg-opacity-10 fs-13"><i
                                                        class="mdi mdi-medal-outline me-1"></i> 2nd</span>
                                            @else
                                                <span
                                                    class="fw-bold text-muted ps-2">#{{ $inventory->firstItem() + $index }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0 me-3">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded-circle fw-bold">
                                                        {{ substr($item->part_name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 fw-bold">{{ $item->part_name }}</h6>
                                                    <p class="text-muted mb-0 small text-uppercase">Commercial Grade Asset
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code class="text-primary fw-medium">{{ $item->part_number }}</code></td>
                                        <td class="text-center">
                                            <h5 class="mb-0 fw-bold text-dark">{{ number_format($item->total_unit) }}</h5>
                                            <small class="text-muted">Units</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('inbound.inventory.index', ['partNumber' => $item->part_number]) }}"
                                                class="btn btn-soft-info btn-sm btn-icon waves-effect waves-light shadow-sm"
                                                data-bs-toggle="tooltip" title="View in Inventory">
                                                <i class="mdi mdi-eye-outline fs-16"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div
                                                    class="avatar-title bg-soft-light text-primary display-4 rounded-circle">
                                                    <i class="mdi mdi-inbox-outline"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No device data found for this selection.</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end">
                        {{ $inventory->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card bg-info bg-gradient border-0 h-100 shadow-sm">
                <div class="card-body p-4 text-white">
                    <div
                        class="avatar-md bg-white bg-opacity-10 rounded-3 mb-4 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-trending-up fs-32"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-3">Demand Insight</h5>
                    <p class="mb-4 opacity-75 small">Urutan perangkat di samping didasarkan pada jumlah stok terbanyak yang
                        dimiliki oleh klien terpilih.</p>

                    <div class="mt-auto">
                        <div class="bg-white bg-opacity-10 p-3 rounded border border-white border-opacity-10 mb-3">
                            <h6 class="text-white small fw-bold mb-2">Total SKU</h6>
                            <h3 class="text-white fw-bold mb-0">{{ $inventory->total() }}</h3>
                        </div>
                        <p class="small mb-0 opacity-50"><i class="mdi mdi-clock-outline align-middle me-1"></i> Data
                            updated in
                            real-time.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
