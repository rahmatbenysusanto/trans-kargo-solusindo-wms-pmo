@extends('layout.index')
@section('title', 'Bulk Import Receiving')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Bulk Import Inbound</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('inbound.receiving.index') }}">Inbound</a></li>
                        <li class="breadcrumb-item active">Bulk Import</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary py-3">
                    <h5 class="card-title mb-0 text-white"><i class="ri-file-excel-2-line me-2"></i>Upload Master Data</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('inbound.receiving.bulkImport.store') }}" method="POST"
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
                                <i class="ri-upload-cloud-2-line me-1"></i> Start Bulk Processing
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
    </div>
@endsection
