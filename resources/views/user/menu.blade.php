@extends('layout.index')
@section('title', 'User Menu')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">User Menu</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item"><a>User</a></li>
                        <li class="breadcrumb-item active"><a>Menu</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">User Menu</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($userHasMenu as $menu)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $menu->name }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            @if($menu->userHasMenu != null)
                                                <input class="form-check-input" type="checkbox" role="switch" onchange="changeMenu('disable', '{{ $menu->id }}')" checked>
                                            @else
                                                <input class="form-check-input" type="checkbox" role="switch" onchange="changeMenu('enable', '{{ $menu->id }}')">
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
        function changeMenu(type, menuId) {
            $.ajax({
                url: '{{ route('user.menu.store') }}',
                method: 'POST',
                data:{
                    _token: '{{ @csrf_token() }}',
                    type: type,
                    menuId: menuId,
                    userId: '{{ $user->id }}'
                },
                success: (res) => {
                    console.log(res)
                }
            });
        }
    </script>
@endsection
