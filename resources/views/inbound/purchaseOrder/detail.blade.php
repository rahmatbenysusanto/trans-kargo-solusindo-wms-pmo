@extends('layout.index')
@section('title', 'Detail Purchase Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Purchase Order Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item">Purchase Order</li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Number</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Client</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->client->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Site Location</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->site_location }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Inbound Type</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->inbound_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Owner Status</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->owner_status }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ \Carbon\Carbon::parse($inbound->created_at)->translatedFormat('d F Y') }}</td>
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
                    <h4 class="card-title mb-0">Product Purchase Order</h4>
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
                                <th>Condition</th>
                                <th>Manufacture Date</th>
                                <th>Warranty End Date</th>
                                <th>EOS Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($inboundDetail as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->part_name }}</td>
                                    <td>{{ $detail->part_number }}</td>
                                    <td>{{ $detail->serial_number }}</td>
                                    <td>{{ $detail->condition }}</td>
                                    <td>{{ $detail->manufacture_date }}</td>
                                    <td>{{ $detail->warranty_end_date }}</td>
                                    <td>{{ $detail->eos_date }}</td>
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
