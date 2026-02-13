@extends('layout.index')
@section('title', 'Edit Asset Lifecycle')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Asset Lifecycle</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('assetLifecycle.index') }}">Asset Lifecycle</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3 mb-4">
                <div
                    class="card-header bg-light-subtle py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-edit-2-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-dark">Update Asset Specs</h5>
                            <span class="text-muted small">SN: <code
                                    class="text-primary font-monospace">{{ $inventory->serial_number }}</code></span>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-label waves-effect waves-light" onclick="editProduct()">
                        <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Update Lifecycle
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div
                                class="p-3 bg-light rounded border-dashed border border-primary-subtle d-flex align-items-center">
                                <i class="ri-information-line text-primary fs-24 me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">{{ $inventory->part_name }}</h6>
                                    <div class="text-muted small">Part Number: <span
                                            class="fw-medium">{{ $inventory->part_number }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label text-muted small text-uppercase">Manufacture Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light"><i
                                            class="ri-calendar-todo-line"></i></span>
                                    <input type="date" class="form-control border-light bg-light"
                                        value="{{ $inventory->manufacture_date }}" id="manufacture_date">
                                </div>
                                <small class="text-muted mt-1 d-block">Initial production date of the asset.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label text-muted small text-uppercase">Warranty End Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light"><i
                                            class="ri-shield-user-line"></i></span>
                                    <input type="date" class="form-control border-light bg-light"
                                        value="{{ $inventory->warranty_end_date }}" id="warranty_end_date">
                                </div>
                                <small class="text-muted mt-1 d-block">Warranty expiration date.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label text-muted small text-uppercase">End of Support (EOS)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light"><i
                                            class="ri-timer-flash-line"></i></span>
                                    <input type="date" class="form-control border-light bg-light"
                                        value="{{ $inventory->eos_date }}" id="eos_date">
                                </div>
                                <small class="text-muted mt-1 d-block">Vendor end of service/support date.</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label text-muted small text-uppercase">Internal Remarks</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light"><i
                                            class="ri-message-3-line"></i></span>
                                    <textarea class="form-control border-light bg-light" rows="3" id="remark"
                                        placeholder="Enter notes or additional lifecycle information ...">{{ $inventory->remark }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle py-3 border-top d-flex justify-content-between">
                    <a href="{{ route('assetLifecycle.index') }}" class="btn btn-soft-secondary d-flex align-items-center">
                        <i class="ri-arrow-left-line me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function editProduct() {
            Swal.fire({
                title: "Confirm Update?",
                text: "Updating lifecycle dates for this asset.",
                icon: "question",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Update Details",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t) => {
                if (t.value) {
                    $.ajax({
                        url: '{{ route('assetLifecycle.update') }}',
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: "{{ $inventory->id }}",
                            manufactureDate: document.getElementById('manufacture_date').value,
                            warrantyEndDate: document.getElementById('warranty_end_date').value,
                            eosDate: document.getElementById('eos_date').value,
                            remark: document.getElementById('remark').value
                        },
                        success: (res) => {
                            if (res.success) {
                                Swal.fire({
                                    title: 'Updated!',
                                    text: 'Asset lifecycle data has been saved.',
                                    icon: 'success'
                                }).then((i) => {
                                    window.location.href =
                                        '{{ route('assetLifecycle.index') }}';
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
