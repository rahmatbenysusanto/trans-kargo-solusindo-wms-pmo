@extends('layout.index')
@section('title', 'Cycle Count')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Cycle Count</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Cycle Count</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Inventory History Logs</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('inventory.cycleCount') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Serial Number</label>
                                <input type="text" name="serialNumber" class="form-control"
                                    placeholder="Search Serial..." value="{{ request('serialNumber') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Part Name</label>
                                <input type="text" name="partName" class="form-control" placeholder="Search Product..."
                                    value="{{ request('partName') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Client</label>
                                <select name="client" class="form-select">
                                    <option value="">All Client</option>
                                    @foreach ($client as $item)
                                        <option value="{{ $item->id }}"
                                            {{ request('client') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">All Type</option>
                                    <option value="Inbound" {{ request('type') == 'Inbound' ? 'selected' : '' }}>Inbound
                                    </option>
                                    <option value="Outbound" {{ request('type') == 'Outbound' ? 'selected' : '' }}>
                                        Outbound</option>
                                    <option value="Movement" {{ request('type') == 'Movement' ? 'selected' : '' }}>
                                        Movement</option>
                                    <option value="PutAway" {{ request('type') == 'PutAway' ? 'selected' : '' }}>PutAway
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-filter-fill align-bottom me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('inventory.cycleCount') }}" class="btn btn-light">
                                        <i class="ri-refresh-line align-bottom me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date & Time</th>
                                    <th scope="col">Product Information</th>
                                    <th scope="col">Serial Number</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Client</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $index => $item)
                                    <tr>
                                        <td>{{ $history->firstItem() + $index }}</td>
                                        <td>
                                            <span
                                                class="text-body fw-medium">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                                            <small
                                                class="text-muted d-block">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            @if ($item->inventory)
                                                <h6 class="fs-14 mb-1">{{ $item->inventory->part_name }}</h6>
                                                <p class="text-muted mb-0">PN: {{ $item->inventory->part_number }}</p>
                                            @else
                                                <span class="text-muted italic">- Product not found -</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->inventory)
                                                <code class="text-primary">{{ $item->inventory->serial_number }}</code>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->inventory && $item->inventory->bin)
                                                <span class="badge badge-soft-primary">
                                                    {{ $item->inventory->bin->storageArea->name }} -
                                                    {{ $item->inventory->bin->storageRak->name }} -
                                                    {{ $item->inventory->bin->storageLantai->name }} -
                                                    {{ $item->inventory->bin->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $typeClass = 'bg-secondary';
                                                if ($item->type == 'Inbound') {
                                                    $typeClass = 'bg-success';
                                                } elseif ($item->type == 'Outbound') {
                                                    $typeClass = 'bg-danger';
                                                } elseif ($item->type == 'Movement') {
                                                    $typeClass = 'bg-info';
                                                }
                                            @endphp
                                            <span class="badge {{ $typeClass }}">{{ $item->type }}</span>
                                        </td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            @if ($item->inventory && $item->inventory->client)
                                                {{ $item->inventory->client->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No history data available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $history->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
