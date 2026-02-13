@extends('layout.index')
@section('title', 'Detail Put Away')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Put Away Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item">Put Away</li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">General Information</h5>
                    <a href="{{ route('inbound.putAway.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 150px">Inbound Number</td>
                                    <td style="width: 20px">:</td>
                                    <td>{{ $inbound->number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Client</td>
                                    <td>:</td>
                                    <td>{{ $inbound->client->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Site Location</td>
                                    <td>:</td>
                                    <td>{{ $inbound->site_location }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 150px">Inbound Type</td>
                                    <td style="width: 20px">:</td>
                                    <td>{{ $inbound->inbound_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Owner Status</td>
                                    <td>:</td>
                                    <td>{{ $inbound->owner_status }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Received Date</td>
                                    <td>:</td>
                                    <td>{{ \Carbon\Carbon::parse($inbound->created_at)->translatedFormat('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Product Details</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Condition</th>
                                    <th class="text-center">Status</th>
                                    <th>Location (Area - Rak - Lantai - Bin)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inboundDetail as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $detail->part_name }}</td>
                                        <td>{{ $detail->part_number }}</td>
                                        <td><code>{{ $detail->serial_number }}</code></td>
                                        <td>
                                            @if ($detail->condition == 'Good')
                                                <span
                                                    class="badge bg-success-subtle text-success">{{ $detail->condition }}</span>
                                            @elseif($detail->condition == 'Used')
                                                <span
                                                    class="badge bg-warning-subtle text-warning">{{ $detail->condition }}</span>
                                            @elseif($detail->condition == 'Defective')
                                                <span
                                                    class="badge bg-danger-subtle text-danger">{{ $detail->condition }}</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary">{{ $detail->condition ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($detail->qty_pa == 1)
                                                <span class="badge bg-success">Put Away Done</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending PA</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($detail->inventory && $detail->inventory->bin)
                                                <span class="text-primary fw-medium">
                                                    {{ $detail->inventory->bin->storageArea->name }} -
                                                    {{ $detail->inventory->bin->storageRak->name }} -
                                                    {{ $detail->inventory->bin->storageLantai->name }} -
                                                    {{ $detail->inventory->bin->name }}
                                                </span>
                                            @else
                                                <span class="text-muted italic">- Not Assigned -</span>
                                            @endif
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
