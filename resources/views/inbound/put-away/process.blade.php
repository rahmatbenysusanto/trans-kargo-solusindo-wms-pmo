@extends('layout.index')
@section('title', 'Process Put Away')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Put Away Process</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item">Put Away</li>
                        <li class="breadcrumb-item active">Process</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Number</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->number }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Client</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->client->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Site Location</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->site_location }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6">
                            <table>
                                <tr>
                                    <td class="fw-bold">Inbound Type</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->inbound_type }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Owner Status</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ $inbound->owner_status }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Date</td>
                                    <td class="fw-bold ps-3">:</td>
                                    <td class="ps-1">{{ \Carbon\Carbon::parse($inbound->created_at)->translatedFormat('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
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
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="listProducts">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Storage</h4>
                        <a class="btn btn-primary btn-sm" onclick="putAwayProcess()">Put Away Process</a>
                    </div>
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
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="listProductPA">

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
        localStorage.clear();
        loadProducts();

        function loadProducts() {
            const dataProducts = @json($inboundDetail);
            const products = [];

            dataProducts.forEach((product) => {
                products.push({
                    id: product.id,
                    partName: product.part_name,
                    partNumber: product.part_number,
                    serialNumber: product.serial_number,
                    select: 0
                });
            });

            localStorage.setItem('products', JSON.stringify(products));
            viewListProducts();
        }

        function viewListProducts() {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            let html = '';

            products.forEach((product, index) => {
                if (product.select === 0) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${product.partName}</td>
                            <td>${product.partNumber}</td>
                            <td>${product.serialNumber}</td>
                            <td><a class="btn btn-secondary btn-sm" onclick="selectProductPA('${index}')">Select</a></td>
                        </tr>
                    `;
                }
            });

            document.getElementById('listProducts').innerHTML = html;
        }

        function selectProductPA(index) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const productPA = JSON.parse(localStorage.getItem('productPA')) ?? [];

            productPA.push(products[index]);

            products[index].select = 1;

            localStorage.setItem('products', JSON.stringify(products));
            localStorage.setItem('productPA', JSON.stringify(productPA));
            viewListProducts();
            viewProductPA();
        }

        function viewProductPA() {
            const productPA = JSON.parse(localStorage.getItem('productPA')) ?? [];
            let html = '';

            productPA.forEach((product, index) => {
                html += `
                     <tr>
                        <td>${index + 1}</td>
                        <td>${product.partName}</td>
                        <td>${product.partNumber}</td>
                        <td>${product.serialNumber}</td>
                        <td><a class="btn btn-danger btn-sm" onclick="deleteProductPA('${index}')">Delete</a></td>
                    </tr>
                `;
            });

            document.getElementById('listProductPA').innerHTML = html;
        }

        function deleteProductPA(index) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const productPA = JSON.parse(localStorage.getItem('productPA')) ?? [];
            const findProductPA = productPA[index];

            // Master Products
            const findProduct = products.find((i) => i.id === findProductPA.id);
            findProduct.select = 0;
            localStorage.setItem('products', JSON.stringify(products));

            productPA.splice(index, 1);
            localStorage.setItem('productPA', JSON.stringify(productPA));
            viewListProducts();
            viewProductPA();
        }

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

        function putAwayProcess() {
            Swal.fire({
                title: "Are you sure?",
                text: "Process Put Away Product",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Process it!",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t)=> {
                if (t.value) {
                    $.ajax({
                        url: '{{ route('inbound.putAway.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            productPA: JSON.parse(localStorage.getItem('productPA')) ?? [],
                            binId: document.getElementById('bin').value
                        },
                        success: (res) => {
                            if (res.status) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Process Put Away Product Success',
                                    icon: 'success'
                                }).then((i) => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Process Put Away Product Failed',
                                    icon: 'error'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endsection
