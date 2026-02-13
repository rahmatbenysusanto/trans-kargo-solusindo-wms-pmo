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
