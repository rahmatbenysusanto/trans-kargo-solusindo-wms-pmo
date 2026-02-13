@extends('layout.index')
@section('title', 'Storage Rak Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Shelving Units</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Storage</a></li>
                        <li class="breadcrumb-item active">Rak (Level 2)</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Rack Identification List</h5>
                        <p class="text-muted mb-0 small text-uppercase">Level 2: Shelving Units within Areas</p>
                    </div>
                    <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addRak">
                        <i class="mdi mdi-plus align-middle me-1"></i> Add New Rak
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="text-center">#</th>
                                    <th><i class="bx bx-grid-alt me-1"></i> Rak Identification</th>
                                    <th>Parent Area</th>
                                    <th class="text-center">Hierarchy</th>
                                    <th class="text-center" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storageRak as $rak)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0 me-3">
                                                    <div class="avatar-title bg-soft-info text-info rounded-circle fw-bold">
                                                        {{ substr($rak->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <h6 class="fs-14 mb-0 fw-bold">{{ $rak->name }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-soft-primary text-primary px-3 py-2 border border-primary border-opacity-10 rounded">
                                                <i class="mdi mdi-map-marker-radius"></i> {{ $rak->storageArea->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <nav aria-label="breadcrumb"
                                                class="breadcrumb-small d-flex justify-content-center">
                                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                                    <li class="breadcrumb-item small text-muted">
                                                        {{ $rak->storageArea->name }}</li>
                                                    <li class="breadcrumb-item small fw-bold text-primary">
                                                        {{ $rak->name }}</li>
                                                </ol>
                                            </nav>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-soft-secondary btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="tooltip" title="Edit">
                                                    <i class="mdi mdi-pencil-outline"></i>
                                                </button>
                                                <button class="btn btn-soft-danger btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="tooltip" title="Delete">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-primary display-4 rounded-circle">
                                                    <i class="mdi mdi-view-grid-plus-outline"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No racks defined yet.</h5>
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

    <!-- Modal Add Rak -->
    <div id="addRak" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="mdi mdi-plus-circle-outline me-2"></i> Register New
                        Rak</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('storage.rak.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Assign to Area</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-primary"><i
                                        class="mdi mdi-map-marker-multiple-outline"></i></span>
                                <select class="form-select border-light bg-light fw-medium" name="area" required>
                                    <option value="">-- Choose Container Area --</option>
                                    @foreach ($storageArea as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Rak Name
                                Identification</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-primary"><i
                                        class="mdi mdi-barcode"></i></span>
                                <input type="text" class="form-control border-light bg-light" name="name"
                                    placeholder="e.g. Rack-01, Shelf-A" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Confirm & Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
