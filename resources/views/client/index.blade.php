@extends('layout.index')
@section('title', 'Client Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Client Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item active">Client</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-10 col-12 mx-auto">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Client Portfolios</h5>
                        <p class="text-muted mb-0 small text-uppercase">Manage your business clients and stakeholders</p>
                    </div>
                    <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#addClientModal">
                        <i class="bx bx-plus-circle align-middle me-1"></i> Register Client
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="text-center">#</th>
                                    <th><i class="bx bx-user-circle me-1"></i> Client Name Identification</th>
                                    <th class="text-center" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client as $item)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0 me-3">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded-circle fw-bold">
                                                        {{ substr($item->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <h6 class="fs-14 mb-0 fw-bold">{{ $item->name }}</h6>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-soft-secondary btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="tooltip" title="Edit Properties">
                                                    <i class="bx bx-edit-alt"></i>
                                                </button>
                                                <button class="btn btn-soft-danger btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="tooltip" title="Remove Client">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-primary display-4 rounded-circle">
                                                    <i class="bx bx-user-x"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No clients registered yet.</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Client -->
    <div id="addClientModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="bx bx-plus-circle me-2"></i> Register New Client
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('client.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Legal Client Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-primary"><i
                                        class="bx bx-rename"></i></span>
                                <input type="text" class="form-control border-light bg-light" name="name"
                                    placeholder="e.g. PT. Global Logistik" required>
                            </div>
                            <small class="text-muted mt-2 d-block">Ensure the name matches official records for
                                documentation.</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Confirm & Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
