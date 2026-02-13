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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-3 col-md-6 border-end">
                            <div class="p-3">
                                <p class="text-muted text-uppercase fw-semibold mb-2 fs-12">Inbound Number</p>
                                <h5 class="mb-0 text-primary">{{ $inbound->number }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 border-end">
                            <div class="p-3">
                                <p class="text-muted text-uppercase fw-semibold mb-2 fs-12">Client Name</p>
                                <h5 class="mb-0">{{ $inbound->client->name }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 border-end">
                            <div class="p-3">
                                <p class="text-muted text-uppercase fw-semibold mb-2 fs-12">Received Date</p>
                                <h5 class="mb-0">
                                    {{ \Carbon\Carbon::parse($inbound->created_at)->translatedFormat('d F Y') }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="p-3 text-center">
                                <p class="text-muted text-uppercase fw-semibold mb-2 fs-12">Progress PA</p>
                                <div class="px-3">
                                    <div class="progress progress-sm animated-progress custom-progress">
                                        <div class="progress-bar bg-success" role="progressbar" id="pa_progress_bar"
                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block" id="pa_progress_text">0% Complete</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                            <i class="ri-list-check-2 fs-20 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Products Ready to Put Away</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                        <table id="tableListProducts" class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Product Information</th>
                                    <th>Serial Number</th>
                                    <th>Condition</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="listProducts"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <i class="ri-map-pin-2-line fs-20 text-success"></i>
                            </div>
                            <h5 class="card-title mb-0 text-success">Destination Storage</h5>
                        </div>
                        <button class="btn btn-success btn-label waves-effect waves-light btn-sm"
                            onclick="putAwayProcess()">
                            <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Confirm Put Away
                        </button>
                    </div>
                </div>
                <div class="card-body border-bottom bg-light-subtle">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">Area</label>
                            <select class="form-select form-select-sm" id="area"
                                onchange="changeStorageArea(this.value)">
                                <option value="">Select Area</option>
                                @foreach ($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">Rak</label>
                            <select class="form-select form-select-sm" id="rak"
                                onchange="changeStorageRak(this.value)">
                                <option value="">Select Rak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">Lantai</label>
                            <select class="form-select form-select-sm" id="lantai"
                                onchange="changeStorageLantai(this.value)">
                                <option value="">Select Lantai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">Bin</label>
                            <select class="form-select form-select-sm border-success" id="bin">
                                <option value="">Select Bin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Product Information</th>
                                    <th>Serial Number</th>
                                    <th>Condition</th>
                                    <th class="text-end text-danger">Action</th>
                                </tr>
                            </thead>
                            <tbody id="listProductPA">
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="ri-drag-drop-line fs-36 opacity-25"></i>
                                        <p class="mt-2 mb-0">No products selected yet.<br>Select products from the left
                                            side to continue.</p>
                                    </td>
                                </tr>
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
        loadProducts();

        $(document).ready(function() {
            $('#tableListProductPA').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: false,
            });

            $('#tableListProducts').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: false,
            });
        });

        function loadProducts() {
            const dataProducts = @json($inboundDetail);
            const products = [];

            dataProducts.forEach((product) => {
                products.push({
                    id: product.id,
                    partName: product.part_name,
                    partNumber: product.part_number,
                    serialNumber: product.serial_number,
                    condition: product.condition,
                    select: 0
                });
            });

            localStorage.setItem('products', JSON.stringify(products));
            viewListProducts();
        }

        function getConditionBadge(condition) {
            if (condition === 'Good') return `<span class="badge bg-success-subtle text-success">${condition}</span>`;
            if (condition === 'Used') return `<span class="badge bg-warning-subtle text-warning">${condition}</span>`;
            if (condition === 'Defective') return `<span class="badge bg-danger-subtle text-danger">${condition}</span>`;
            return `<span class="badge bg-secondary-subtle text-secondary">${condition || '-'}</span>`;
        }

        function viewListProducts() {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const productPA = JSON.parse(localStorage.getItem('productPA')) ?? [];
            const totalProducts = products.length;
            const selectedCount = productPA.length;

            // Update progress bar
            const progress = totalProducts > 0 ? (selectedCount / totalProducts) * 100 : 0;
            const progressBar = document.getElementById('pa_progress_bar');
            if (progressBar) {
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
                document.getElementById('pa_progress_text').innerText = Math.round(progress) + '% Complete';
            }

            let html = '';
            let count = 0;
            products.forEach((product, index) => {
                if (product.select === 0) {
                    count++;
                    html += `
                        <tr>
                            <td><span class="text-muted fw-medium">${count}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="fs-14 mb-1 text-dark">${product.partName}</h6>
                                        <p class="text-muted mb-0 small">PN: ${product.partNumber}</p>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-primary font-monospace">${product.serialNumber}</code></td>
                            <td>${getConditionBadge(product.condition)}</td>
                            <td class="text-end">
                                <button class="btn btn-soft-info btn-icon btn-sm" onclick="selectProductPA('${index}')">
                                    <i class="ri-arrow-right-line"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }
            });

            if (count === 0) {
                html =
                    `<tr><td colspan="5" class="text-center py-4 text-muted"><i class="ri-checkbox-circle-line text-success fs-24 d-block mb-1"></i> All products are selected</td></tr>`;
            }

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

            if (productPA.length === 0) {
                html = `<tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="ri-drag-drop-line fs-36 opacity-25"></i>
                        <p class="mt-2 mb-0">No products selected yet.<br>Select products from the left side to continue.</p>
                    </td>
                </tr>`;
            } else {
                productPA.forEach((product, index) => {
                    html += `
                         <tr>
                            <td><span class="text-muted fw-medium">${index + 1}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="fs-14 mb-1 text-dark">${product.partName}</h6>
                                        <p class="text-muted mb-0 small">PN: ${product.partNumber}</p>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-success font-monospace">${product.serialNumber}</code></td>
                            <td>${getConditionBadge(product.condition)}</td>
                            <td class="text-end">
                                <button class="btn btn-soft-danger btn-icon btn-sm" onclick="deleteProductPA('${index}')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('listProductPA').innerHTML = html;

            // Sync progress bar
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const totalProducts = products.length;
            const progress = totalProducts > 0 ? (productPA.length / totalProducts) * 100 : 0;
            const progressBar = document.getElementById('pa_progress_bar');
            if (progressBar) {
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
                document.getElementById('pa_progress_text').innerText = Math.round(progress) + '% Complete';
            }
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
            }).then(async (t) => {
                if (t.value) {
                    $.ajax({
                        url: '{{ route('inbound.putAway.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            productPA: JSON.parse(localStorage.getItem('productPA')) ?? [],
                            binId: document.getElementById('bin').value,
                            inboundId: '{{ $inbound->id }}'
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
