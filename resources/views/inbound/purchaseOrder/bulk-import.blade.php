@extends('layout.index')
@section('title', 'Bulk Import Receiving')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">{{ isset($groups) ? 'Preview Bulk Import' : 'Bulk Import Inbound' }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('inbound.receiving.index') }}">Inbound</a></li>
                        <li class="breadcrumb-item active">Bulk Import</li>
                    </ol>
                </div>
            </div>
        </div>

        @if (!isset($groups))
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary py-3">
                        <h5 class="card-title mb-0 text-white"><i class="ri-file-excel-2-line me-2"></i>Upload Master Data</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('inbound.receiving.bulkImport.preview') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">Select Master Excel File</label>
                                <input type="file" class="form-control" name="file" accept=".xls,.xlsx" required>
                                <div class="form-text text-muted mt-2">
                                    <i class="ri-information-line me-1"></i> System will automatically group records based on
                                    <strong>Date, Client, PIC, and Site Location</strong>.
                                </div>
                            </div>

                            <div class="alert alert-info border-0 shadow-sm mb-4">
                                <h6 class="alert-heading fw-bold mb-2"><i class="ri-list-check me-2"></i>Header Mapping
                                    Guidelines:</h6>
                                <ul class="mb-0 small">
                                    <li>Column B: <strong>Date in</strong> (Format: DD-MMM-YY or Date Object)</li>
                                    <li>Column C: <strong>PN#</strong> (Material/Part Name)</li>
                                    <li>Column D: <strong>SN#</strong> (Serial Number)</li>
                                    <li>Column E: <strong>Alokasi dari Site</strong> (Site Location)</li>
                                    <li>Column F: <strong>PIC</strong> (PIC Name)</li>
                                    <li>Column G: <strong>By</strong> (Client Name)</li>
                                    <li>Column I: <strong>Remarks</strong> (Detail Remarks)</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('inbound.receiving.index') }}" class="btn btn-light fw-medium">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    <i class="ri-upload-cloud-2-line me-1"></i> View Preview
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 border-start border-info border-3">
                    <div class="card-body">
                        <h5 class="card-title text-info mb-3">How it works?</h5>
                        <p class="text-muted">This feature is designed to process your "Master Data" which often contains mixed
                            records. The system will:</p>
                        <ol class="text-muted small">
                            <li>Read all rows from the uploaded Excel file.</li>
                            <li>Scan names for <strong>Clients</strong> and <strong>PICs</strong>. If they don't exist in our
                                database, the system will automatically create them for you.</li>
                            <li>Group all products that arrived on the same date, from the same client, under the same PIC into
                                <strong>one Transaction (Inbound ID)</strong>.</li>
                            <li>Generate sequential Inbound Numbers for each group.</li>
                        </ol>
                        <div class="mt-4 p-3 bg-light rounded border">
                            <p class="mb-0 fw-medium small text-dark"><i class="ri-lightbulb-line text-warning me-2"></i>Tip:
                                Make sure your header names match the image you provided for best accuracy.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white"><i class="ri-eye-line me-2"></i>Bulk Import Preview</h5>
                        <span class="badge bg-white text-info">{{ count($groups) }} Groups Identified</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>PIC</th>
                                        <th>Site Location</th>
                                        <th>AWB/Remarks</th>
                                        <th>Total Items</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groups as $key => $group)
                                        <tr>
                                            <td>{{ $group['received_at'] }}</td>
                                            <td><span class="badge bg-primary-subtle text-primary">{{ $group['client_name'] }}</span></td>
                                            <td>{{ $group['pic_name'] }}</td>
                                            <td>{{ $group['site_location'] }}</td>
                                            <td>{{ $group['awb'] ?: '-' }}</td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">{{ count($group['products']) }} Items</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}">
                                                    View Items
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="collapse{{ $loop->index }}">
                                            <td colspan="7" class="bg-light p-0">
                                                <div class="p-3">
                                                    <table class="table table-sm table-bordered m-0 small bg-white">
                                                        <thead>
                                                            <tr>
                                                                <th>Part Name</th>
                                                                <th>Serial Number</th>
                                                                <th>Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($group['products'] as $product)
                                                                <tr>
                                                                    <td>{{ $product['part_name'] }}</td>
                                                                    <td>{{ $product['sn'] }}</td>
                                                                    <td>{{ $product['remarks'] }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning border-0 shadow-sm mt-4">
                            <i class="ri-error-warning-line me-2"></i> Please review the records above carefully. Once confirmed, the system will create <strong>{{ count($groups) }}</strong> inbound transactions and update the inventory.
                        </div>

                        <form action="{{ route('inbound.receiving.bulkImport.confirm') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('inbound.receiving.bulkImport') }}" class="btn btn-light fw-medium">
                                    <i class="ri-arrow-left-line me-1"></i> Back to Upload
                                </a>
                                <button type="submit" class="btn btn-success px-5 fw-bold btn-confirm-bulk">
                                    <i class="ri-check-double-line me-1"></i> Confirm & Start Process
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.querySelector('.btn-confirm-bulk')?.addEventListener('click', function(e) {
            this.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i> Processing...';
            this.disabled = true;
            this.closest('form').submit();
        });
    </script>
@endsection
