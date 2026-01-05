@extends('layout.index')
@section('title', 'Create Return To Client')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Return To Client</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Return</a></li>
                        <li class="breadcrumb-item">Return To Client</li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Return To Client Data</h4>
                        <a class="btn btn-primary btn-sm" onclick="createReturn()">Create Return To Client</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3 mb-3">
                            <label class="form-label">Client</label>
                            <select class="form-control" id="client">
                                <option value="">-- Choose Client --</option>
                                @foreach($client as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Site Location</label>
                            <input type="text" class="form-control" id="siteLocation" placeholder="Site Location ...">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Received By</label>
                            <input type="text" class="form-control" id="receivedBy" placeholder="Received By ...">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Return Date</label>
                            <input type="date" class="form-control" id="returnDate" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Courier</label>
                            <input type="text" class="form-control" id="courier" placeholder="Courier ...">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Tracking Number / AWB</label>
                            <input type="text" class="form-control" id="trackingNumber" placeholder="Tracking Number ...">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control" id="remarks">
                        </div>
                        <div class="col-3 mb-3">
                            <label class="form-label">Condition</label>
                            <select class="form-control" id="condition">
                                <option value="">-- Choose Condition --</option>
                                <option>Good</option>
                                <option>Scrape</option>
                                <option>Damage</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Products List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableProductsOutbound" class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Serial Number</th>
                                <th>Client</th>
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
                    <h4 class="card-title mb-0">Products Return To Client</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableListProducts" class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Serial Number</th>
                                <th>Client</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="productsOutbound">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        localStorage.clear();
        loadProduct();

        $(document).ready(function () {
            $('#tableProductsOutbound').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
            });

            $('#tableListProducts').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
            });
        });

        function loadProduct() {
            const dataProducts = @json($inventory);
            const products = [];

            dataProducts.forEach((product) => {
                products.push({
                    id: product.id,
                    partName: product.part_name,
                    partNumber: product.part_number,
                    serialNumber: product.serial_number,
                    client: product.inbound_detail.inbound.client.name,
                    select: 0
                });
            });

            localStorage.setItem('products', JSON.stringify(products));
            viewProducts();
        }

        function viewProducts() {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            let html = '';

            products.forEach((product, index) => {
                if (product.select === 0) {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <div class="fw-bold">${product.partName}</div>
                                <div>${product.partNumber}</div>
                            </td>
                            <td>${product.serialNumber}</td>
                            <td>${product.client}</td>
                            <td><a class="btn btn-secondary btn-sm" onclick="selectProduct('${index}')">Select</a></td>
                        </tr>
                    `;
                }
            });

            document.getElementById('listProducts').innerHTML = html;
        }

        function selectProduct(index) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];

            products[index].select = 1;
            productsOutbound.push(products[index]);

            localStorage.setItem('products', JSON.stringify(products));
            localStorage.setItem('productsOutbound', JSON.stringify(productsOutbound));

            viewProducts();
            viewProductsOutbound();
        }

        function viewProductsOutbound() {
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
            let html = '';

            productsOutbound.forEach((product, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="fw-bold">${product.partName}</div>
                            <div>${product.partNumber}</div>
                        </td>
                        <td>${product.serialNumber}</td>
                        <td>${product.client}</td>
                        <td><a class="btn btn-danger btn-sm" onclick="deleteProduct('${index}')">Delete</a></td>
                    </tr>
                `;
            });

            document.getElementById('productsOutbound').innerHTML = html;
        }

        function deleteProduct(index) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
            const findProductOutbound = productsOutbound[index];
            const findProduct = products.find((i) => i.id === findProductOutbound.id);

            productsOutbound.splice(index, 1);
            findProduct.select = 0;

            localStorage.setItem('products', JSON.stringify(products));
            localStorage.setItem('productsOutbound', JSON.stringify(productsOutbound));

            viewProducts();
            viewProductsOutbound();
        }

        function createReturn() {
            Swal.fire({
                title: "Are you sure?",
                text: "Create Return To Client",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Create it!",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t)=> {
                if (t.value) {

                    $.ajax({
                        url: '{{ route('return.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            client: document.getElementById('client').value,
                            siteLocation: document.getElementById('siteLocation').value,
                            receivedBy: document.getElementById('receivedBy').value,
                            deliveryDate: document.getElementById('deliveryDate').value,
                            courier: document.getElementById('courier').value,
                            trackingNumber: document.getElementById('trackingNumber').value,
                            remarks: document.getElementById('remarks').value,
                            condition: document.getElementById('condition').value,
                            products: JSON.parse(localStorage.getItem('productsOutbound')) ?? []
                        },
                        success: (res) => {
                            if (res.status) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Create Outbound Successfully',
                                    icon: 'success'
                                }).then((i) => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Create Outbound Failed',
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
