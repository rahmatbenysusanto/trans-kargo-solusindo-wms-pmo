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
                    <h4 class="card-title mb-0">Storage</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label">Area</label>
                            <select class="form-control" id="area">
                                <option value="">-- Choose Area --</option>
                                @foreach($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Rak</label>
                            <select class="form-control" id="area">
                                <option value="">-- Choose Rak --</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Lantai</label>
                            <select class="form-control" id="area">
                                <option value="">-- Choose Lantai --</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label">Bin</label>
                            <select class="form-control" id="area">
                                <option value="">-- Choose Bin --</option>
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
    </script>
@endsection
