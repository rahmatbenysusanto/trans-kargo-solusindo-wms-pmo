@extends('layout.index')
@section('title', 'Create Stock Movement')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Stock Movement</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item">Stock Movement</li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">List Products</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Storage</th>
                                    <th>Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Product Change Storage</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label">Area</label>
                            <select class="form-control" id="area" onchange="changeStorageArea(this.value)">
                                <option value="">-- Choose Area --</option>
                                @foreach($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Rak</label>
                            <select class="form-control" id="rak" onchange="changeStorageRak(this.value)">

                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Lantai</label>
                            <select class="form-control" id="lantai" onchange="changeStorageLantai(this.value)">

                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Bin</label>
                            <select class="form-control" id="bin">

                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-header">
                    <h4 class="card-title mb-0">Product Put Away</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Storage</th>
                                    <th>Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function changeStorageArea(valueArea) {
            $.ajax({
                url: '{{ route('storage.rak.find') }}',
                method: 'GET',
                data: {
                    areaId: valueArea
                },
                success: (res) => {
                    const dataRak = res.data;
                    let html = `<option value="">-- Choose Rak --</option>`;

                    dataRak.forEach((rak) => {
                        html += `<option value="${rak.id}">${rak.name}</option>`;
                    });

                    document.getElementById('rak').innerHTML = html;
                }
            });
        }

        function changeStorageRak(value) {
            $.ajax({
                url: '{{ route('storage.lantai.find') }}',
                method: 'GET',
                data: {
                    areaId: document.getElementById('area').value,
                    rakId: value
                },
                success: (res) => {
                    const dataLantai = res.data;
                    let html = `<option value="">-- Choose Lantai --</option>`;

                    dataLantai.forEach((lantai) => {
                        html += `<option value="${lantai.id}">${lantai.name}</option>`;
                    });

                    document.getElementById('lantai').innerHTML = html;
                }
            });
        }

        function changeStorageLantai(value) {
            $.ajax({
                url: '{{ route('storage.bin.find') }}',
                method: 'GET',
                data: {
                    lantaiId: value
                },
                success: (res) => {
                    const dataBin = res.data;
                    let html = `<option value="">-- Choose Bin --</option>`;

                    dataBin.forEach((bin) => {
                        html += `<option value="${bin.id}">${bin.name}</option>`;
                    });

                    document.getElementById('bin').innerHTML = html;
                }
            });
        }
    </script>
@endsection
