@extends('layout.index')
@section('title', 'User Access Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">User Access Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item active">User Control</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">System Workforce</h5>
                        <p class="text-muted mb-0 small text-uppercase">Monitor and manage user permissions and account
                            statuses</p>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn btn-primary fw-bold shadow-sm">
                        <i class="bx bx-user-plus align-middle me-1 fs-16"></i> Add New User
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 60px;" class="text-center">#</th>
                                    <th>Personnel Detail</th>
                                    <th>Contact Info</th>
                                    <th class="text-center">Account Status</th>
                                    <th class="text-center">Privileges</th>
                                    <th class="text-center" style="width: 150px;">Management</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user as $index => $item)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $user->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0 me-3">
                                                    <div
                                                        class="avatar-title bg-soft-info text-info rounded-circle fw-bold fs-16">
                                                        {{ substr($item->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 fw-bold">{{ $item->name }}</h6>
                                                    <span class="text-muted small">@ {{ $item->username }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fs-13 fw-medium text-dark"><i
                                                        class="bx bx-envelope me-1 text-muted"></i>{{ $item->email }}</span>
                                                <span class="fs-12 text-muted"><i
                                                        class="bx bx-phone me-1 text-muted"></i>{{ $item->no_hp }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'active')
                                                <span
                                                    class="badge bg-soft-success text-success px-3 py-2 border border-success border-opacity-10 rounded-pill fs-11">
                                                    <i class="bx bxs-check-circle me-1"></i> ACTIVE
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-soft-danger text-danger px-3 py-2 border border-danger border-opacity-10 rounded-pill fs-11">
                                                    <i class="bx bxs-x-circle me-1"></i> INACTIVE
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('user.menu', ['id' => $item->id]) }}"
                                                class="btn btn-soft-secondary btn-sm waves-effect px-3">
                                                <i class="bx bx-key me-1"></i> Permissions
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('user.edit', ['id' => $item->id]) }}"
                                                    class="btn btn-soft-info btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="tooltip" title="Edit Profile">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                                <button class="btn btn-soft-danger btn-sm btn-icon waves-effect"
                                                    data-bs-toggle="tooltip" title="Deactivate">
                                                    <i class="bx bx-power-off"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="avatar-lg mx-auto mb-3">
                                                <div class="avatar-title bg-light text-primary display-4 rounded-circle">
                                                    <i class="bx bx-group"></i>
                                                </div>
                                            </div>
                                            <h5 class="text-muted">No users found in the system.</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($user->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-end">
                            {{ $user->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
