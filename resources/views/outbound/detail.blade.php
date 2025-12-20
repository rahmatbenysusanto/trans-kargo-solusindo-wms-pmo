@extends('layout.index')
@section('title', 'Detail Outbound')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Detail Outbound</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Outbound</a></li>
                        <li class="breadcrumb-item active"><a>Detail</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Outbound Data</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <table>
                                <tr>
                                    <td class="fw-bold">Number</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Client</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->client->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Site Location</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->site_location }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-4">
                            <table>
                                <tr>
                                    <td class="fw-bold">Delivery Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ \Carbon\Carbon::parse($outbound->delivery_date)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Received By</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->received_by }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Created By</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->user->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-4">
                            <table>
                                <tr>
                                    <td class="fw-bold">Courier</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->courier }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tracking Number / AWB</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->tracking_number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Remarks</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $outbound->remarks }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Product List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Part Name</th>
                                <th>Part Number</th>
                                <th>Serial Number</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($outboundDetail as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->inventory->part_name }}</td>
                                <td>{{ $detail->inventory->part_number }}</td>
                                <td>{{ $detail->inventory->serial_number }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
