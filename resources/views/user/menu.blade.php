@extends('layout.index')
@section('title', 'User Permissions Configuration')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Personnel Permissions</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item"><a>User Control</a></li>
                        <li class="breadcrumb-item active">Access Rights</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-8 col-12 mx-auto">
            <div class="card shadow-sm border-0 border-top border-secondary border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-secondary text-white rounded-circle fw-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">{{ $user->name }}'s Access Control</h5>
                        <p class="text-muted mb-0 small text-uppercase">Toggle module visibility and feature authorization
                        </p>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;" class="text-center">#</th>
                                    <th><i class="bx bx-category me-1"></i> Module / Function Name</th>
                                    <th class="text-center" style="width: 120px;">Grant Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userHasMenu as $menu)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0 me-2"
                                                    style="width: 28px; height: 28px;">
                                                    <div
                                                        class="avatar-title bg-soft-secondary text-secondary rounded fs-14">
                                                        <i class="bx bx-chevron-right"></i>
                                                    </div>
                                                </div>
                                                <span class="fw-medium">{{ $menu->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="form-check form-switch form-switch-md justify-content-center d-flex">
                                                @if ($menu->userHasMenu != null)
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="menuSwitch{{ $menu->id }}"
                                                        onchange="changeMenu(this, '{{ $menu->id }}')" checked>
                                                @else
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="menuSwitch{{ $menu->id }}"
                                                        onchange="changeMenu(this, '{{ $menu->id }}')">
                                                @endif
                                                <label class="form-check-label ms-2 d-none d-sm-block small text-muted"
                                                    for="menuSwitch{{ $menu->id }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light-subtle border-top py-3">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="bx bx-info-circle me-1"></i>
                        <span>Changes are applied in real-time. No save required.</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('user.index') }}" class="btn btn-link text-muted"><i class="bx bx-arrow-back me-1"></i>
                    Back to User List</a>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function changeMenu(element, menuId) {
            const type = element.checked ? 'enable' : 'disable';

            // Add a temporary pulse animation to show it's working
            const row = element.closest('tr');
            row.style.transition = 'background-color 0.3s';
            row.style.backgroundColor = 'rgba(64, 81, 137, 0.05)';

            $.ajax({
                url: '{{ route('user.menu.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ @csrf_token() }}',
                    type: type,
                    menuId: menuId,
                    userId: '{{ $user->id }}'
                },
                success: (res) => {
                    setTimeout(() => {
                        row.style.backgroundColor = '';
                    }, 500);
                    // Use standard toast if available in project
                    console.log('Permission updated:', type, menuId);
                },
                error: (err) => {
                    element.checked = !element.checked; // Revert on failure
                    row.style.backgroundColor = 'rgba(240, 101, 72, 0.1)';
                    setTimeout(() => {
                        row.style.backgroundColor = '';
                    }, 500);
                    alert('Failed to update permission. Please try again.');
                }
            });
        }
    </script>
@endsection
