@extends('layout.index')
@section('title', 'PIC Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">PIC Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Master Data</a></li>
                        <li class="breadcrumb-item active">PIC</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-10 col-12 mx-auto">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">PIC List</h5>
                        <p class="text-muted mb-0 small text-uppercase">Manage Person In Charge for operations</p>
                    </div>
                    <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addPicModal">
                        <i class="bx bx-plus-circle align-middle me-1"></i> Add PIC
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="text-center">#</th>
                                    <th><i class="bx bx-user me-1"></i> PIC Name</th>
                                    <th class="text-center" style="width: 150px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pic as $item)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">
                                            {{ ($pic->currentPage() - 1) * $pic->perPage() + $loop->iteration }}</td>
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
                                                    data-bs-toggle="modal" data-bs-target="#editPicModal{{ $item->id }}"
                                                    title="Edit PIC">
                                                    <i class="bx bx-edit-alt"></i>
                                                </button>
                                                <button class="btn btn-soft-danger btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deletePicModal{{ $item->id }}" title="Remove PIC">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit PIC -->
                                    <div id="editPicModal{{ $item->id }}" class="modal fade" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-primary py-3">
                                                    <h5 class="modal-title text-white fw-bold"><i
                                                            class="bx bx-edit-alt me-2"></i> Edit PIC
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <form action="{{ route('pic.update', $item->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label
                                                                class="form-label fw-bold text-muted small text-uppercase">PIC
                                                                Name</label>
                                                            <div class="input-group">
                                                                <span
                                                                    class="input-group-text bg-light border-light text-primary"><i
                                                                        class="bx bx-rename"></i></span>
                                                                <input type="text"
                                                                    class="form-control border-light bg-light"
                                                                    name="name" value="{{ $item->name }}"
                                                                    placeholder="e.g. John Doe" required>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="btn btn-light px-4"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-primary px-4 fw-bold shadow-sm">Save
                                                                Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Delete PIC -->
                                    <div id="deletePicModal{{ $item->id }}" class="modal fade" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-danger py-3">
                                                    <h5 class="modal-title text-white fw-bold"><i
                                                            class="bx bx-trash me-2"></i> Delete PIC
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4 text-center">
                                                    <div class="avatar-lg mx-auto mb-3">
                                                        <div
                                                            class="avatar-title bg-soft-danger text-danger display-4 rounded-circle">
                                                            <i class="bx bx-error-circle"></i>
                                                        </div>
                                                    </div>
                                                    <h4 class="fw-bold">Are you sure?</h4>
                                                    <p class="text-muted">You are about to delete PIC <span
                                                            class="fw-bold">{{ $item->name }}</span>. This action cannot
                                                        be undone.</p>
                                                    <form action="{{ route('pic.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST') {{-- Using POST for destroy as per simplified routes if needed, but I'll use DELETE in routes --}}
                                                        <div class="d-flex justify-content-center gap-2 mt-4">
                                                            <button type="button" class="btn btn-light px-4"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit"
                                                                class="btn btn-danger px-4 fw-bold shadow-sm">Delete
                                                                Now</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-primary display-4 rounded-circle">
                                                    <i class="bx bx-user-x"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No PIC registered yet.</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($pic->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-end">
                            {{ $pic->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Add PIC -->
    <div id="addPicModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary py-3">
                    <h5 class="modal-title text-white fw-bold"><i class="bx bx-plus-circle me-2"></i> Add New PIC
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('pic.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">PIC Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-primary"><i
                                        class="bx bx-rename"></i></span>
                                <input type="text" class="form-control border-light bg-light" name="name"
                                    placeholder="e.g. John Doe" required>
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
