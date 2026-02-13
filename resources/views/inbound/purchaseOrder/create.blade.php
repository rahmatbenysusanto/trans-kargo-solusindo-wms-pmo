@extends('layout.index')
@section('title', 'Create Receiving')

@section('content')
    <style>
        .condition-dropdown .btn {
            border-radius: 20px;
            font-weight: 500;
            padding: 3px 12px;
            font-size: 11px;
            border: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .btn-good {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .btn-good:hover {
            background-color: #c1d7cc;
            color: #0f5132;
        }

        .btn-used {
            background-color: #fff3cd;
            color: #664d03;
        }

        .btn-used:hover {
            background-color: #f0e3bd;
            color: #664d03;
        }

        .btn-defective {
            background-color: #f8d7da;
            color: #842029;
        }

        .btn-defective:hover {
            background-color: #e8c7ca;
            color: #842029;
        }

        .dropdown-item i {
            margin-right: 8px;
            font-size: 14px;
            vertical-align: middle;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Receiving</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inbound</a></li>
                        <li class="breadcrumb-item">Receiving</li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-primary" onclick="createInbound()">Create Inbound</a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Client</label>
                                <select class="form-select" name="client" id="client">
                                    <option value="">-- Choose Client --</option>
                                    @foreach ($client as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Inbound Type</label>
                                <select class="form-select" name="inbound_type" id="inbound_type">
                                    <option>-- Choose Type --</option>
                                    <option>Dismantle</option>
                                    <option>Relocation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Ownership Status</label>
                                <select class="form-select" name="ownership_status" id="ownership_status">
                                    <option value="">-- Choose Ownership Status --</option>
                                    <option>Milik Client</option>
                                    <option>Titipan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Site Location</label>
                                <textarea class="form-control" name="site_location" id="site_location"></textarea>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-animate border-start border-primary border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Products</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4" id="stat_total">0</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="ri-shopping-basket-line text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-animate border-start border-success border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Condition Good</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-success" id="stat_good">0</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success-subtle rounded fs-3">
                                        <i class="ri-checkbox-circle-line text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-animate border-start border-warning border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Condition Used</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-warning" id="stat_used">0</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning-subtle rounded fs-3">
                                        <i class="ri-error-warning-line text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-animate border-start border-danger border-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Condition Defective
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-danger" id="stat_defective">0</h4>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-danger-subtle rounded fs-3">
                                        <i class="ri-close-circle-line text-danger"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm">
                            <h4 class="card-title mb-0">Product Inbound List</h4>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ asset('assets/Template Inbound.xlsx') }}" download
                                    class="btn btn-soft-success btn-sm"><i
                                        class="ri-download-2-line align-bottom me-1"></i>
                                    Template</a>
                                <button class="btn btn-soft-secondary btn-sm" onclick="importExcelModal()"><i
                                        class="ri-file-excel-line align-bottom me-1"></i> Import</button>
                                <button class="btn btn-primary btn-sm" onclick="addProductModal()"><i
                                        class="ri-add-line align-bottom me-1"></i> Add Product</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-bottom">
                    <div class="row g-3">
                        <div class="col-xxl-4 col-sm-6">
                            <div class="search-box">
                                <input type="text" class="form-control" id="searchItem"
                                    placeholder="Search by SN, PN, or Name..." onkeyup="handleSearch()">
                                <i class="ri-search-line search-icon"></i>
                            </div>
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
                    <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-3">
                        <div id="paginationInfo" class="text-muted small"></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0" id="paginationList"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modals -->
    <div id="addProductModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
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
                                <input type="text" class="form-control" id="add_part_name"
                                    placeholder="Part Name ...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" id="add_serial_number"
                                    placeholder="Serial Number ...">
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
                                <input type="text" class="form-control" id="add_part_number"
                                    placeholder="Part Number ...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Condition</label>
                                <select class="form-control" id="add_condition">
                                    <option>-- Choose Condition --</option>
                                    <option>Good</option>
                                    <option>Used</option>
                                    <option>Defective</option>
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
    <div id="importExcelModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
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
                    <button type="button" class="btn btn-primary" onclick="importProducts()">Import Product By
                        Excel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_index">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Part Name</label>
                                <input type="text" class="form-control" id="edit_part_name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" id="edit_serial_number">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Manufacture Date</label>
                                <input type="date" class="form-control" id="edit_manufacture_date">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">EOS Date</label>
                                <input type="date" class="form-control" id="edit_eos_date">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Part Number</label>
                                <input type="text" class="form-control" id="edit_part_number">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Condition</label>
                                <select class="form-control" id="edit_condition">
                                    <option value="Good">Good</option>
                                    <option value="Used">Used</option>
                                    <option value="Defective">Defective</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Warranty End Date</label>
                                <input type="date" class="form-control" id="edit_warranty_end_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveEditedProduct()">Save Changes</button>
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
        let currentPage = 1;
        const itemsPerPage = 10;
        let searchTerm = '';

        function handleSearch() {
            searchTerm = document.getElementById('searchItem').value.toLowerCase();
            currentPage = 1;
            viewProducts();
        }

        function updateStats(products) {
            const stats = {
                total: products.length,
                good: products.filter(p => p.condition === 'Good').length,
                used: products.filter(p => p.condition === 'Used').length,
                defective: products.filter(p => p.condition === 'Defective').length
            };

            document.getElementById('stat_total').innerText = stats.total;
            document.getElementById('stat_good').innerText = stats.good;
            document.getElementById('stat_used').innerText = stats.used;
            document.getElementById('stat_defective').innerText = stats.defective;
        }

        function getConditionConfig(condition) {
            switch (condition) {
                case 'Good':
                    return {
                        class: 'btn-good',
                        icon: 'ri-checkbox-circle-fill',
                            bg: 'bg-success'
                    };
                case 'Used':
                    return {
                        class: 'btn-used',
                        icon: 'ri-error-warning-fill',
                            bg: 'bg-warning'
                    };
                case 'Defective':
                    return {
                        class: 'btn-defective',
                        icon: 'ri-close-circle-fill',
                            bg: 'bg-danger'
                    };
                default:
                    return {
                        class: 'btn-light',
                        icon: 'ri-question-line',
                            bg: 'bg-secondary'
                    };
            }
        }

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
            const allProducts = JSON.parse(localStorage.getItem('products')) ?? [];
            updateStats(allProducts);

            const filteredProducts = allProducts.filter(product =>
                (product.partName || '').toLowerCase().includes(searchTerm) ||
                (product.partNumber || '').toLowerCase().includes(searchTerm) ||
                (product.serialNumber || '').toLowerCase().includes(searchTerm)
            );

            const totalItems = filteredProducts.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            if (currentPage > totalPages && totalPages > 0) {
                currentPage = totalPages;
            }

            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
            const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

            let html = '';

            if (paginatedProducts.length === 0) {
                html = `<tr><td colspan="9" class="text-center py-4">
                    <div class="text-muted">
                        <i class="ri-search-line fs-24"></i>
                        <p class="mt-2">No products found matching your search</p>
                    </div>
                </td></tr>`;
            } else {
                paginatedProducts.forEach((product, i) => {
                    const originalIndex = allProducts.findIndex(p => p === product);
                    const displayIndex = startIndex + i;

                    html += `
                        <tr>
                            <td>${ displayIndex + 1 }</td>
                            <td><span class="fw-medium">${ product.partName }</span></td>
                            <td>${ product.partNumber }</td>
                            <td><code class="text-primary">${ product.serialNumber }</code></td>
                            <td>
                                <div class="dropdown condition-dropdown">
                                    <button class="btn btn-sm dropdown-toggle ${getConditionConfig(product.condition).class}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="${getConditionConfig(product.condition).icon}"></i>
                                        ${product.condition}
                                    </button>
                                    <ul class="dropdown-menu shadow">
                                        <li>
                                            <a class="dropdown-item text-success" href="javascript:void(0)" onclick="updateProductCondition(${originalIndex}, 'Good')">
                                                <i class="ri-checkbox-circle-fill"></i> Good
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-warning" href="javascript:void(0)" onclick="updateProductCondition(${originalIndex}, 'Used')">
                                                <i class="ri-error-warning-fill"></i> Used
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="updateProductCondition(${originalIndex}, 'Defective')">
                                                <i class="ri-close-circle-fill"></i> Defective
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>${ product.manufactureDate || '-' }</td>
                            <td>${ product.warrantyEndDate || '-' }</td>
                            <td>${ product.eosDate || '-' }</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-soft-info btn-sm" onclick="editProductModal(${originalIndex})">
                                        <i class="ri-edit-2-fill"></i>
                                    </button>
                                    <button class="btn btn-soft-danger btn-sm" onclick="deleteProduct('${originalIndex}')">
                                        <i class="ri-delete-bin-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('listProducts').innerHTML = html;
            renderPagination(totalPages, totalItems, startIndex, endIndex);
        }

        function renderPagination(totalPages, totalItems, startIndex, endIndex) {
            const paginationList = document.getElementById('paginationList');
            const paginationInfo = document.getElementById('paginationInfo');

            if (totalItems === 0) {
                paginationList.innerHTML = '';
                paginationInfo.innerHTML = 'Showing 0 to 0 of 0 entries';
                return;
            }

            paginationInfo.innerHTML = `Showing ${startIndex + 1} to ${endIndex} of ${totalItems} entries`;

            let html = '';

            // Previous button
            html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">Previous</a>
            </li>`;

            // Page numbers
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                </li>`;
            }

            // Next button
            html += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">Next</a>
            </li>`;

            paginationList.innerHTML = html;
        }

        function changePage(page) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const totalPages = Math.ceil(products.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            viewProducts();
        }

        function updateProductCondition(index, value) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            if (products[index]) {
                products[index].condition = value;
                localStorage.setItem('products', JSON.stringify(products));
                viewProducts(); // Refresh the view to show changes
            }
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
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];

                const jsonData = XLSX.utils.sheet_to_json(worksheet, {
                    defval: "",
                });

                const products = JSON.parse(localStorage.getItem('products')) ?? [];
                jsonData.forEach((item) => {
                    products.push({
                        partName: item['PN#'],
                        partNumber: item['PN#'],
                        serialNumber: item['SN#'],
                        condition: 'Good',
                        manufactureDate: item['Manufacture Date'] || '',
                        warrantyEndDate: item['Warranty End Date'] || '',
                        eosDate: item['EOS Date'] || ''
                    });
                });
                localStorage.setItem('products', JSON.stringify(products));
                viewProducts();
            };

            reader.readAsArrayBuffer(file);
            document.getElementById('fileExcel').value = '';
            $('#importExcelModal').modal('hide');
        }

        function editProductModal(index) {
            const products = JSON.parse(localStorage.getItem('products')) ?? [];
            const product = products[index];

            if (product) {
                document.getElementById('edit_index').value = index;
                document.getElementById('edit_part_name').value = product.partName;
                document.getElementById('edit_part_number').value = product.partNumber;
                document.getElementById('edit_serial_number').value = product.serialNumber;
                document.getElementById('edit_condition').value = product.condition;
                document.getElementById('edit_manufacture_date').value = product.manufactureDate || '';
                document.getElementById('edit_warranty_end_date').value = product.warrantyEndDate || '';
                document.getElementById('edit_eos_date').value = product.eosDate || '';

                $('#editProductModal').modal('show');
            }
        }

        function saveEditedProduct() {
            const index = document.getElementById('edit_index').value;
            const products = JSON.parse(localStorage.getItem('products')) ?? [];

            if (products[index]) {
                products[index] = {
                    partName: document.getElementById('edit_part_name').value,
                    partNumber: document.getElementById('edit_part_number').value,
                    serialNumber: document.getElementById('edit_serial_number').value,
                    condition: document.getElementById('edit_condition').value,
                    manufactureDate: document.getElementById('edit_manufacture_date').value,
                    warrantyEndDate: document.getElementById('edit_warranty_end_date').value,
                    eosDate: document.getElementById('edit_eos_date').value
                };

                localStorage.setItem('products', JSON.stringify(products));
                viewProducts();
                $('#editProductModal').modal('hide');

                Swal.fire({
                    title: 'Success',
                    text: 'Product updated successfully',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }

        function createInbound() {
            Swal.fire({
                title: "Are you sure?",
                text: "Create Inbound",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                confirmButtonText: "Yes, Create it!",
                buttonsStyling: false,
                showCloseButton: true
            }).then(async (t) => {
                if (t.value) {

                    $.ajax({
                        url: '{{ route('inbound.receiving.store') }}',
                        method: 'POST',
                        contentType: 'application/json',
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: JSON.stringify({
                            client: document.getElementById('client').value,
                            inboundType: document.getElementById('inbound_type').value,
                            ownershipStatus: document.getElementById('ownership_status').value,
                            siteLocation: document.getElementById('site_location').value,
                            remarks: document.getElementById('remarks').value,
                            products: JSON.parse(localStorage.getItem('products')) ?? []
                        }),
                        success: (res) => {
                            if (res.status) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Create Inbound Success',
                                    icon: 'success'
                                }).then((i) => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Create Inbound Failed',
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
