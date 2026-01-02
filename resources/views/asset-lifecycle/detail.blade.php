@extends('layout.index')
@section('title', 'Edit Asset Lifecycle')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Asset Lifecycle</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a>Edit</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-primary" onclick="editProduct()">Update</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 mb-3">
                            <label class="form-label">Part Name</label>
                            <input type="text" class="form-control" value="{{ $inventory->part_name }}" readonly>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Part Number</label>
                            <input type="text" class="form-control" value="{{ $inventory->part_number }}" readonly>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" class="form-control" value="{{ $inventory->serial_number }}" readonly>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Manufacture Date</label>
                            <input type="date" class="form-control" value="{{ $inventory->manufacture_date }}" id="manufacture_date">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">Warranty End Date</label>
                            <input type="date" class="form-control" value="{{ $inventory->warranty_end_date }}" id="warranty_end_date">
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">EOS Date</label>
                            <input type="date" class="form-control" value="{{ $inventory->eos_date }}" id="eos_date">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Remark</label>
                            <textarea class="form-control" rows="2" id="remark">{{ $inventory->remark }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function editProduct() {
            Swal.fire({
                title: "Are you sure?",
                text: `Edit Asset Lifecycle`,
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Edit",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t)=> {
                if (t.value) {

                    $.ajax({
                        url: '{{ route('assetLifecycle.update') }}',
                        method: 'POST',
                        data:{
                            _token: "{{ csrf_token() }}",
                            id: "{{ $inventory->id }}",
                            manufactureDate: document.getElementById('manufacture_date').value,
                            warrantyEndDate: document.getElementById('warranty_end_date').value,
                            eosDate: document.getElementById('eos_date').value,
                            remark: document.getElementById('remark').value
                        },
                        success: (res) => {
                            Swal.fire({
                                title: 'Success',
                                text: 'Edit Asset Lifecycle Success',
                                icon: 'success'
                            }).then((i) => {
                                window.location.reload();
                            });
                        }
                    });

                }
            });
        }
    </script>
@endsection
