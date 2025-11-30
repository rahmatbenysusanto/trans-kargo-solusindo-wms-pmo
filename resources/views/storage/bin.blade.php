@extends('layout.index')
@section('title', 'Storage Bin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Storage Bin</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Storage</a></li>
                        <li class="breadcrumb-item active">Bin</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBin">Add Bin</a>
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
                                    <th>Lantai</th>
                                    <th>Bin</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($storageBin as $bin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bin->storageArea->name }}</td>
                                        <td>{{ $bin->storageRak->name }}</td>
                                        <td>{{ $bin->storageLantai->name }}</td>
                                        <td>{{ $bin->name }}</td>
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

    <div id="addBin" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Add Bin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('storage.bin.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Area Name</label>
                            <select class="form-control" name="area" onchange="changeArea(this.value)">
                                <option value="">-- Choose Area --</option>
                                @foreach($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rak Name</label>
                            <select class="form-control" name="rak" id="rak" onchange="changeRak(this.value)">

                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lantai Name</label>
                            <select class="form-control" name="lantai" id="lantai">

                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bin Name</label>
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

@section('js')
    <script>
        function changeArea(areaId) {
            $.ajax({
                url: '{{ route('storage.rak.find') }}',
                method: 'GET',
                data: {
                    areaId: areaId
                },
                success: (res) => {
                    const data = res.data;
                    let html = '<option value="">-- Choose Rak --</option>';

                    data.forEach((item) => {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });

                    document.getElementById('rak').innerHTML = html;
                }
            });
        }

        function changeRak(rakId) {
            $.ajax({
                url: '{{ route('storage.lantai.find') }}',
                method: 'GET',
                data: {
                    rakId: rakId
                },
                success: (res) => {
                    const data = res.data;
                    let html = '<option value="">-- Choose Lantai --</option>';

                    data.forEach((item) => {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });

                    document.getElementById('lantai').innerHTML = html;
                }
            });
        }
    </script>
@endsection
