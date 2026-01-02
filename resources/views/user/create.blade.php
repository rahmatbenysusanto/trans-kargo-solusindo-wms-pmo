@extends('layout.index')
@section('title', 'Create User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create User</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item"><a>User</a></li>
                        <li class="breadcrumb-item active"><a>Create</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Create User</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Username ..." required>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name ..." required>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="user@mail.com ..." required>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">No HP</label>
                                <input type="number" class="form-control" name="no_hp" placeholder="089 ..." required>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">Password</label>
                                <input type="text" class="form-control" name="password" placeholder="Password ..." required>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Add User</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
