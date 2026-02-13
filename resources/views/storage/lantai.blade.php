@extends('layout.index')
@section('title', 'Storage Lantai Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Floor Levels</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Storage</a></li>
                        <li class="breadcrumb-item active">Lantai (Level 3)</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Floor Level Identification</h5>
                        <p class="text-muted mb-0 small text-uppercase">Level 3: Vertical levels within Racks</p>
                    </div>
                    <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addLantai">
                        <i class="mdi mdi-plus align-middle me-1"></i> Add New Lantai
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="text-center">#</th>
                                    <th><i class="bx bx-layer me-1"></i> Lantai Level</th>
                                    <th>Location Path</th>
                                    <th class="text-center">Hierarchy Visualization</th>
                                    <th class="text-center" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storageLantai as $lantai)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0 me-3">
                                                    <div
                                                        class="avatar-title bg-soft-warning text-warning rounded-circle fw-bold">
                                                        {{ substr($lantai->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <h6 class="fs-14 mb-0 fw-bold">{{ $lantai->name }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span class="text-primary fw-medium"><i
                                                        class="mdi mdi-map-marker-outline"></i>
                                                    {{ $lantai->storageArea->name }}</span>
                                                <span class="text-muted"><i class="mdi mdi-view-column-outline"></i>
                                                    {{ $lantai->storageRak->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-1">
                                                <small class="text-muted">{{ $lantai->storageArea->name }}</small>
                                                <i class="mdi mdi-chevron-right mx-1 text-muted"></i>
                                                <small class="text-muted">{{ $lantai->storageRak->name }}</small>
                                                <i class="mdi mdi-chevron-right mx-1 text-muted"></i>
                                                <small class="fw-bold text-primary">{{ $lantai->name }}</small>
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
                                        <td colspan="5" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-primary display-4 rounded-circle">
                                                    <i class="mdi mdi-layers-off-outline"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No floor levels defined yet.</h5>
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

    <!-- Modal Add Lantai -->
    <div id="addLantai" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="mdi mdi-plus-circle-outline me-2"></i> Register New
                        Lantai
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('storage.lantai.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">1. Select Area</label>
                            <select class="form-select border-light bg-light fw-medium" name="area"
                                onchange="changeArea(this.value)" required>
                                <option value="">-- Choose Container Area --</option>
                                @foreach ($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">2. Select Rak</label>
                            <select class="form-select border-light bg-light fw-medium" name="rak" id="rak"
                                required>
                                <option value="">-- Choose Rak First --</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">3. Lantai Name/Level</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-primary"><i
                                        class="mdi mdi-layers-triple-outline"></i></span>
                                <input type="text" class="form-control border-light bg-light" name="name"
                                    placeholder="e.g. Floor 1, Level A" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Confirm &
                                Create</button>
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
                }
            });
        }
    </script>
@endsection
