@extends('layout.index')
@section('title', 'Storage Bin Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Storage Bins</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Storage</a></li>
                        <li class="breadcrumb-item active">Bin (Level 4 - Precise)</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Bin Inventory Locations</h5>
                        <p class="text-muted mb-0 small text-uppercase">Level 4: Most Precise Storage Unit</p>
                    </div>
                    <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBin">
                        <i class="mdi mdi-plus align-middle me-1"></i> Add New Bin
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="text-center">#</th>
                                    <th><i class="bx bx-package align-middle me-1"></i> Bin Identification</th>
                                    <th>Absolute Path</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storageBin as $bin)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0 me-3">
                                                    <div
                                                        class="avatar-title bg-soft-primary text-primary rounded fs-3 shadow-none">
                                                        <i class="bx bx-box"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 fw-bold">{{ $bin->name }}</h6>
                                                    <span class="badge bg-soft-success text-success small">Ready for
                                                        Stock</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                <span
                                                    class="badge bg-light text-dark fw-medium border shadow-none px-2">{{ $bin->storageArea->name }}</span>
                                                <i class="mdi mdi-chevron-right text-muted"></i>
                                                <span
                                                    class="badge bg-light text-dark fw-medium border shadow-none px-2">{{ $bin->storageRak->name }}</span>
                                                <i class="mdi mdi-chevron-right text-muted"></i>
                                                <span
                                                    class="badge bg-light text-dark fw-medium border shadow-none px-2">{{ $bin->storageLantai->name }}</span>
                                                <i class="mdi mdi-chevron-right text-muted"></i>
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary fw-bold border border-primary border-opacity-25 px-2">{{ $bin->name }}</span>
                                            </div>
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
                                        <td colspan="4" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-primary display-4 rounded-circle">
                                                    <i class="mdi mdi-archive-outline"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No bins defined yet.</h5>
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

    <!-- Modal Add Bin -->
    <div id="addBin" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="mdi mdi-plus-circle-outline me-2"></i> Register New
                        Bin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('storage.bin.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">1. Parent Area</label>
                            <select class="form-select border-light bg-light fw-medium" name="area"
                                onchange="changeArea(this.value)" required>
                                <option value="">-- Choose Area --</option>
                                @foreach ($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">2. Select Rak</label>
                                <select class="form-select border-light bg-light fw-medium" name="rak" id="rak"
                                    onchange="changeRak(this.value)" required>
                                    <option value="">-- Choose Rak --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">3. Select Lantai</label>
                                <select class="form-select border-light bg-light fw-medium" name="lantai" id="lantai"
                                    required>
                                    <option value="">-- Choose Lantai --</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">4. Precise Bin Name</label>
                            <div class="input-group shadow-none">
                                <span class="input-group-text bg-light border-light text-primary"><i
                                        class="mdi mdi-package-variant"></i></span>
                                <input type="text" class="form-control border-light bg-light fw-bold" name="name"
                                    placeholder="e.g. BIN-A1-01" required>
                            </div>
                            <small class="text-muted mt-2 d-block">This is the final location where products will be
                                placed.</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Enable Bin
                                Location</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function changeArea(areaId) {
            if (!areaId) return;
            $.ajax({
                url: '{{ route('storage.rak.find') }}',
                method: 'GET',
                data: {
                    areaId: areaId
                },
                success: (res) => {
                    const data = res.data;
                    let html = '<option value="">-- Select Rak --</option>';
                    data.forEach((item) => {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });
                    document.getElementById('rak').innerHTML = html;
                    document.getElementById('lantai').innerHTML =
                        '<option value="">-- Choose Rak First --</option>';
                }
            });
        }

        function changeRak(rakId) {
            if (!rakId) return;
            $.ajax({
                url: '{{ route('storage.lantai.find') }}',
                method: 'GET',
                data: {
                    rakId: rakId
                },
                success: (res) => {
                    const data = res.data;
                    let html = '<option value="">-- Select Lantai --</option>';
                    data.forEach((item) => {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });
                    document.getElementById('lantai').innerHTML = html;
                }
            });
        }
    </script>
@endsection
