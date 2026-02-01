@extends('layout.index')
@section('title', 'Mass Edit Asset Lifecycle')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Mass Edit Asset Lifecycle</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Asset Lifecycle</a></li>
                        <li class="breadcrumb-item active"><a>Mass Edit</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Upload Excel File</h5>
                        <a href="{{ route('assetLifecycle.downloadTemplate') }}" class="btn btn-info btn-sm">
                            <i class="ri-download-line align-bottom me-1"></i> Download Template
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label for="excel_file" class="form-label">Choose Excel File</label>
                                <input type="file" name="excel_file" id="excel_file" class="form-control"
                                    accept=".xlsx, .xls">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100" id="btnUpload">
                                    <i class="ri-upload-2-line align-bottom me-1"></i> Upload & Preview
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3" id="previewSection" style="display: none;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Preview Data</h5>
                    <button type="button" class="btn btn-success" id="btnProcess" disabled>
                        <i class="ri-check-line align-bottom me-1"></i> Process Mass Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap align-middle mb-0" id="previewTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Serial Number</th>
                                    <th>Manufacture Date</th>
                                    <th>Warranty End Date</th>
                                    <th>EOS Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let uploadedData = [];

            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const btnUpload = $('#btnUpload');

                btnUpload.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...'
                );

                $.ajax({
                    url: "{{ route('assetLifecycle.uploadExcel') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        btnUpload.prop('disabled', false).html(
                            '<i class="ri-upload-2-line align-bottom me-1"></i> Upload & Preview'
                        );

                        if (response.success) {
                            uploadedData = response.data;
                            renderTable(uploadedData);
                            $('#previewSection').show();
                            $('#btnProcess').prop('disabled', uploadedData.length === 0);
                        } else {
                            Swal.fire('Error', response.message || 'Failed to parse file',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        btnUpload.prop('disabled', false).html(
                            '<i class="ri-upload-2-line align-bottom me-1"></i> Upload & Preview'
                        );
                        Swal.fire('Error', 'An error occurred during upload', 'error');
                    }
                });
            });

            function renderTable(data) {
                const tbody = $('#previewTable tbody');
                tbody.empty();

                data.forEach(item => {
                    const statusBadge = item.exists ?
                        '<span class="badge bg-success">Found</span>' :
                        '<span class="badge bg-danger">Not Found</span>';

                    tbody.append(`
                    <tr>
                        <td>${item.serial_number}</td>
                        <td>${item.manufacture_date || '-'}</td>
                        <td>${item.warranty_end_date || '-'}</td>
                        <td>${item.eos_date || '-'}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `);
                });
            }

            $('#btnProcess').on('click', function() {
                const btn = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will update the asset lifecycle data for found serial numbers.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, process it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...'
                        );

                        $.ajax({
                            url: "{{ route('assetLifecycle.processMassEdit') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                data: uploadedData
                            },
                            success: function(response) {
                                btn.prop('disabled', false).html(
                                    '<i class="ri-check-line align-bottom me-1"></i> Process Mass Edit'
                                );

                                if (response.success) {
                                    Swal.fire('Success', response.message, 'success')
                                        .then(() => {
                                            window.location.href =
                                                "{{ route('assetLifecycle.index') }}";
                                        });
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function() {
                                btn.prop('disabled', false).html(
                                    '<i class="ri-check-line align-bottom me-1"></i> Process Mass Edit'
                                );
                                Swal.fire('Error',
                                    'An error occurred during processing', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
