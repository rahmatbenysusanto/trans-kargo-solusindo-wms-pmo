@extends('layout.index')
@section('title', 'Storage Rak')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Storage Rak</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Storage</a></li>
                        <li class="breadcrumb-item active">Rak</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRak">Add Rak</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Area</th>
                                    <th>Rak</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($storageRak as $rak)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $rak->storageArea->name }}</td>
                                        <td>{{ $rak->name }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="btn btn-sm btn-secondary">Edit</a>
                                                <a class="btn btn-sm btn-danger">Delete</a>
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
    </div>

    <div id="addRak" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Add Rak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('storage.rak.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Area Name</label>
                            <select class="form-control" name="area">
                                <option value="">-- Choose Area --</option>
                                @foreach($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rak Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
