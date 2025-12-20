@extends('layout.index')
@section('title', 'Product History')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Product History</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item">Inventory List</li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Inbound Data</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Number</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->inboundDetail->inbound->number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Client</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->inboundDetail->inbound->client->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Site Location</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->inboundDetail->inbound->site_location }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Inbound Type</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->inboundDetail->inbound->inbound_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Owner Status</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->inboundDetail->inbound->owner_status }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ \Carbon\Carbon::parse($inventory->inboundDetail->inbound->created_at)->translatedFormat('d F Y') }}</td>
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
                    <h4 class="card-title mb-0">Product Data</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Part Name</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->part_name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Part Number</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->part_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Serial Number</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->serial_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Condition</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->condition }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Manufacture Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->manufacture_date }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Warranty End Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->warranty_end_date }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">EOS Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->eos_date }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Storage</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inventory->bin->storageArea->name }} - {{ $inventory->bin->storageRak->name }} - {{ $inventory->bin->storageLantai->name }} - {{ $inventory->bin->name }}</td>
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
                    <h4 class="card-title mb-0">Product History</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($history as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}</td>
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
