@extends('layout.index')
@section('title', 'Create Purchase Order')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Purchase Order</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item">Purchase Order</li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-primary">Create Inbound</a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Client</label>
                                <select class="form-select" name="client">
                                    <option value="">-- Choose Client --</option>
                                    @foreach($client as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Inbound Type</label>
                                <select class="form-select" name="inbound_type">
                                    <option>-- Choose Type --</option>
                                    <option>Dismantle</option>
                                    <option>Relocation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Ownership Status</label>
                                <select class="form-select" name="ownership_status">
                                    <option value="">-- Choose Ownership Status --</option>
                                    <option>Milik Client</option>
                                    <option>Titipan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Site Location</label>
                                <textarea class="form-control" name="site_location"></textarea>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Product Inbound</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ asset('assets/Template Inbound.xlsx') }}" download class="btn btn-success btn-sm">Download File Excel</a>
                            <a class="btn btn-secondary btn-sm" onclick="importExcelModal()">Import Excel</a>
                            <a class="btn btn-primary btn-sm" onclick="addProductModal()">Add Product</a>
                        </div>
                    </div>
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
                                    <th>Condition</th>
                                    <th>Manufacture Date</th>
                                    <th>Warranty End Date</th>
                                    <th>EOS Date</th>
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
    </div>

    <!-- Add Product Modals -->
    <div id="addProductModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Part Name</label>
                                <input type="text" class="form-control" id="add_part_name" placeholder="Part Name ...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" id="add_serial_number" placeholder="Serial Number ...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Manufacture Date</label>
                                <input type="date" class="form-control" id="add_manufacture_date">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">EOS Date</label>
                                <input type="date" class="form-control" id="add_eos_date">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Part Number</label>
                                <input type="text" class="form-control" id="add_part_number" placeholder="Part Number ...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Condition</label>
                                <select class="form-control" id="add_condition">
                                    <option>-- Choose Condition --</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Warranty End Date</label>
                                <input type="date" class="form-control" id="add_warranty_end_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addProduct()">Add Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Excel Modals -->
    <div id="importExcelModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Import Product Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <input type="file" class="form-control" accept=".xls,.xlsx" id="fileExcel">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="importProducts()">Import Product By Excel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('assets/js/xlsx.full.min.js') }}"></script>

    <script>
        localStorage.clear();

        function addProductModal() {
            $('#addProductModal').modal('show');
        }

        function addProduct() {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];

            products.push({
                partName: document.getElementById('add_part_name').value,
                partNumber: document.getElementById('add_part_number').value,
                serialNumber: document.getElementById('add_serial_number').value,
                condition: document.getElementById('add_condition').value,
                manufactureDate: document.getElementById('add_manufacture_date').value,
                warrantyEndDate: document.getElementById('add_warranty_end_date').value,
                eosDate: document.getElementById('add_eos_date').value
            });

            localStorage.setItem('products', JSON.stringify(products));
            viewProducts();

            document.getElementById('add_part_name').value = '';
            document.getElementById('add_part_number').value = '';
            document.getElementById('add_serial_number').value = '';
            document.getElementById('add_condition').value = '';
            document.getElementById('add_manufacture_date').value = '';
            document.getElementById('add_warranty_end_date').value = '';
            document.getElementById('add_eos_date').value = '';
            $('#addProductModal').modal('hide');
        }

        function viewProducts() {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];

            let html = '';

            products.forEach((product, index) => {
                html += `
                    <tr>
                        <td>${ index + 1 }</td>
                        <td>${ product.partName }</td>
                        <td>${ product.partNumber }</td>
                        <td>${ product.serialNumber }</td>
                        <td>${ product.condition }</td>
                        <td>${ product.manufactureDate }</td>
                        <td>${ product.warrantyEndDate }</td>
                        <td>${ product.eosDate }</td>
                        <td><a class="btn btn-danger btn-sm" onclick="deleteProduct('${index}')">Delete</a></td>
                    </tr>
                `;
            });

            document.getElementById('listProducts').innerHTML = html;
        }

        function deleteProduct(index) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            products.splice(index, 1);
            localStorage.setItem('products', JSON.stringify(products));
            viewProducts();
        }

        function importExcelModal() {
            $('#importExcelModal').modal('show');
        }

        function importProducts() {
            const EXCEL_MAX_SIZE_MB = 10;

            const fileInput = document.getElementById('fileExcel');
            const file = fileInput?.files?.[0];

            if (!file) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Pilih file Excel terlebih dahulu.',
                    icon: 'warning',
                });
                return false;
            }
            const allowedExt = ['.xls', '.xlsx'];
            const ext = file.name.toLowerCase().slice(file.name.lastIndexOf('.'));
            if (!allowedExt.includes(ext)) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Hanya boleh .xls atau .xlsx',
                    icon: 'warning',
                });
                return false;
            }
            const maxBytes = EXCEL_MAX_SIZE_MB * 1024 * 1024;
            if (file.size > maxBytes) {
                Swal.fire({
                    title: 'Warning',
                    text: `Ukuran file melebihi ${EXCEL_MAX_SIZE_MB} MB`,
                    icon: 'warning',
                });
                return false;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });

                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];

                const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                    defval: "",
                    range: 1
                });

                const products = JSON.parse(localStorage.getItem('products')) ?? [];
                jsonData.forEach((item) => {
                    products.push({
                        partName: item['Part Name'],
                        partNumber: item['Part Number'],
                        serialNumber: item['Serial Number'],
                        condition: item['Condition'],
                        manufactureDate: item['Manufacture Date'],
                        warrantyEndDate: item['Warranty End Date'],
                        eosDate: item['EOS Date']
                    });
                });
                localStorage.setItem('products', JSON.stringify(products));
                viewProducts();
            };

            reader.readAsArrayBuffer(file);
            document.getElementById('fileExcel').value = '';
            $('#importExcelModal').modal('hide');
        }

    </script>
@endsection
