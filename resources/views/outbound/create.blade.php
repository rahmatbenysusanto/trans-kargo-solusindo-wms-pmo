@extends('layout.index')
@section('title', 'Create Outbound')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create Outbound</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Outbound</a></li>
                        <li class="breadcrumb-item active"><a>Create</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light-subtle py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="ri-truck-line fs-18"></i>
                                </div>
                            </div>
                            <h5 class="card-title mb-0">Outbound Information</h5>
                        </div>
                        <button class="btn btn-primary btn-label waves-effect waves-light" onclick="createOutbound()">
                            <i class="ri-send-plane-2-line label-icon align-middle fs-16 me-2"></i> Submit Outbound
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Client</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i
                                        class="ri-user-star-line"></i></span>
                                <select class="form-select border-light bg-light" id="client">
                                    <option value="">-- Choose Client --</option>
                                    @foreach ($client as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Site Location</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i class="ri-map-pin-line"></i></span>
                                <input type="text" class="form-control border-light bg-light" id="siteLocation"
                                    placeholder="Enter Site ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Received By</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i
                                        class="ri-user-follow-line"></i></span>
                                <input type="text" class="form-control border-light bg-light" id="receivedBy"
                                    placeholder="Recipient Name ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Delivery Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i
                                        class="ri-calendar-event-line"></i></span>
                                <input type="date" class="form-control border-light bg-light" id="deliveryDate"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Courier Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i class="ri-e-bike-2-line"></i></span>
                                <input type="text" class="form-control border-light bg-light" id="courier"
                                    placeholder="Expedition / Courier ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">AWB / Tracking Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i class="ri-qr-code-line"></i></span>
                                <input type="text" class="form-control border-light bg-light" id="trackingNumber"
                                    placeholder="Tracking Number ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Remarks</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light"><i class="ri-chat-4-line"></i></span>
                                <input type="text" class="form-control border-light bg-light" id="remarks"
                                    placeholder="Notes ...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Delivery Note Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white border-primary"><i
                                        class="ri-file-text-line"></i></span>
                                <input type="text" class="form-control border-primary bg-primary-subtle fw-bold"
                                    id="deliveryNoteNumber" value="Loading..." readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small text-uppercase">Total Selected Items</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white border-primary"><i
                                        class="ri-hand-coin-line"></i></span>
                                <input type="number" class="form-control border-primary bg-primary-subtle fw-bold"
                                    id="qtyProduct" value="0" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                            <i class="ri-list-unordered fs-20 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Inventory Available</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 bg-light-subtle border-bottom">
                        <div class="search-box">
                            <input type="text" class="form-control" id="searchInventory"
                                placeholder="Search Part Name, PN, or SN...">
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Product Information</th>
                                    <th>Serial Number</th>
                                    <th>Client</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="listProducts"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-2 bg-light-subtle" id="inventoryPagination"></div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                            <i class="ri-checkbox-circle-line fs-20 text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 text-success">Selected for Outbound</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- BULK SN INPUT -->
                    <div class="p-3 border-bottom bg-warning-subtle" id="bulkSnSection">
                        <div class="d-flex align-items-center mb-0">
                            <div class="d-flex align-items-center flex-shrink-0">
                                <i class="ri-barcode-line fs-16 me-2 text-warning"></i>
                                <h6 class="mb-0 fw-bold" style="cursor:pointer" onclick="toggleBulkSn()">Bulk SN Input</h6>
                            </div>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <small class="text-muted" id="bulkSnCount">0 SN</small>
                                <button class="btn btn-sm btn-link p-0 text-muted" type="button" onclick="toggleBulkSn()">
                                    <i class="ri-arrow-up-s-line fs-18" id="bulkSnToggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div id="bulkSnContent" class="mt-2">
                            <textarea class="form-control form-control-sm font-monospace" id="bulkSnTextarea" rows="4"
                                placeholder="Paste serial numbers here (one per line)&#10;Example:&#10;SN-00123&#10;SN-00124&#10;SN-00125"></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <button class="btn btn-sm btn-soft-info me-1" onclick="clearBulkSn()">
                                        <i class="ri-eraser-line"></i> Clear
                                    </button>
                                    <span id="bulkSnDuplicateInfo" class="small text-muted" style="display:none;"></span>
                                </div>
                                <button class="btn btn-success btn-sm" onclick="processBulkSn(this)">
                                    <i class="ri-add-line me-1"></i> Add to List
                                </button>
                            </div>
                            <div id="bulkSnResult" class="mt-2" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="tableProductsOutbound" class="table table-hover table-nowrap align-middle mb-0">
                            <thead class="bg-light text-muted text-success">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Product Information</th>
                                    <th>Serial Number</th>
                                    <th>Client</th>
                                    <th class="text-end text-danger">Action</th>
                                </tr>
                            </thead>
                            <tbody id="productsOutbound">
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="ri-shopping-cart-2-line fs-32 opacity-25"></i>
                                        <p class="mt-2 mb-0">No products selected.<br>Search and select from the left list.
                                        </p>
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
        let currentPage = 1;
        let searchQuery = '';

        $(document).ready(function() {
            fetchInventory();
            fetchNextDNNumber();

            $('#searchInventory').on('keyup', function() {
                searchQuery = $(this).val();
                currentPage = 1;
                fetchInventory();
            });
        });

        function fetchInventory(page = 1) {
            currentPage = page;
            $.ajax({
                url: '{{ route('outbound.inventory.search') }}',
                method: 'GET',
                data: {
                    search: searchQuery,
                    page: page
                },
                success: function(res) {
                    renderInventory(res);
                }
            });
        }

        function fetchNextDNNumber() {
            $.ajax({
                url: '{{ route('outbound.nextDNNumber') }}',
                method: 'GET',
                success: function(res) {
                    document.getElementById('deliveryNoteNumber').value = res.delivery_note_number;
                },
                error: function() {
                    document.getElementById('deliveryNoteNumber').value = 'Auto-generated';
                }
            });
        }

        // ===== BULK SN INPUT =====
        $('#bulkSnTextarea').on('input', function() {
            const sns = getSerialNumbersFromTextarea();
            $('#bulkSnCount').text(sns.length + ' SN');

            // Check for duplicates in textarea
            const unique = new Set(sns);
            if (unique.size < sns.length) {
                $('#bulkSnDuplicateInfo').text(sns.length - unique.size + ' duplicate(s) found').show();
            } else {
                $('#bulkSnDuplicateInfo').hide();
            }
        });

        function getSerialNumbersFromTextarea() {
            const text = document.getElementById('bulkSnTextarea').value;
            if (!text.trim()) return [];
            return text.split('\n')
                .map(s => s.trim())
                .filter(s => s.length > 0);
        }

        function toggleBulkSn() {
            const content = document.getElementById('bulkSnContent');
            const icon = document.getElementById('bulkSnToggleIcon');
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.className = 'ri-arrow-up-s-line fs-18';
            } else {
                content.style.display = 'none';
                icon.className = 'ri-arrow-down-s-line fs-18';
            }
        }

        function clearBulkSn() {
            document.getElementById('bulkSnTextarea').value = '';
            $('#bulkSnCount').text('0 SN');
            $('#bulkSnDuplicateInfo').hide();
            $('#bulkSnResult').hide();
        }

        function processBulkSn(btn) {
            const sns = getSerialNumbersFromTextarea();
            if (sns.length === 0) {
                Swal.fire('Error', 'Please enter at least 1 Serial Number', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            $.ajax({
                url: '{{ route('outbound.inventory.searchBySN') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    serial_numbers: sns
                },
                success: function(res) {
                    const found = res.found || [];
                    const notFound = res.not_found || [];

                    if (found.length > 0) {
                        const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
                        const existingIds = new Set(productsOutbound.map(p => p.id));
                        let addedCount = 0;

                        found.forEach(product => {
                            if (!existingIds.has(product.id)) {
                                productsOutbound.push({
                                    id: product.id,
                                    partName: product.part_name,
                                    partNumber: product.part_number,
                                    partDescription: product.part_description,
                                    serialNumber: product.serial_number,
                                    client: product.client ? product.client.name : '-'
                                });
                                addedCount++;
                                existingIds.add(product.id);
                            }
                        });

                        localStorage.setItem('productsOutbound', JSON.stringify(productsOutbound));
                        viewProductsOutbound();
                        fetchInventory(currentPage);
                    }

                    // Show result feedback
                    let resultHtml = '';
                    if (found.length > 0) {
                        const added = found.length;
                        resultHtml += `<div class="alert alert-success py-2 mb-1 small"><i class="ri-check-line me-1"></i>${added} SN(s) ditemukan & ditambahkan</div>`;
                    }
                    if (notFound.length > 0) {
                        resultHtml += `<div class="alert alert-warning py-2 mb-1 small"><i class="ri-error-warning-line me-1"></i>Tidak ditemukan: <strong>${notFound.join(', ')}</strong></div>`;
                    }

                    const resultDiv = document.getElementById('bulkSnResult');
                    resultDiv.innerHTML = resultHtml;
                    resultDiv.style.display = 'block';

                    // Clear textarea jika semua ditemukan
                    if (notFound.length === 0) {
                        document.getElementById('bulkSnTextarea').value = '';
                        $('#bulkSnCount').text('0 SN');
                        $('#bulkSnDuplicateInfo').hide();
                    }

                    // Auto-hide result after 8s
                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                    }, 8000);
                },
                error: function() {
                    Swal.fire('Error', 'Gagal memproses serial number', 'error');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-add-line me-1"></i> Add to List';
                }
            });
        }
        // ===== END BULK SN =====

        function renderInventory(res) {
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
            const selectedIds = productsOutbound.map(p => p.id);

            let html = '';
            res.data.forEach((product, index) => {
                const isSelected = selectedIds.includes(product.id);
                const clientName = product.client ? product.client.name : '-';

                html += `
                    <tr>
                        <td><span class="text-muted fw-medium">${(res.current_page - 1) * res.per_page + index + 1}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="fs-14 mb-1 text-dark">${product.part_name}</h6>
                                    <p class="text-muted mb-0 small">PN: ${product.part_number}</p>
                                    <p class="text-muted mb-0 small text-truncate" style="max-width: 200px;">Desc: ${product.part_description || '-'}</p>
                                </div>
                            </div>
                        </td>
                        <td><code class="text-primary font-monospace">${product.serial_number}</code></td>
                        <td><span class="badge badge-soft-primary px-2">${clientName}</span></td>
                        <td class="text-end">
                            ${isSelected ? 
                                `<span class="badge badge-soft-success">Selected</span>` :
                                `<button class="btn btn-soft-info btn-icon btn-sm" onclick="selectProductAjax(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                                                    <i class="ri-arrow-right-line"></i>
                                                </button>`
                            }
                        </td>
                    </tr>
                `;
            });

            if (res.data.length === 0) {
                html = `<tr><td colspan="5" class="text-center py-4 text-muted">No products found.</td></tr>`;
            }

            document.getElementById('listProducts').innerHTML = html;

            // Render Pagination
            let paginationHtml = `
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-end mb-0">
                        <li class="page-item ${res.prev_page_url ? '' : 'disabled'}">
                            <a class="page-link" href="javascript:void(0)" onclick="fetchInventory(${res.current_page - 1})">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="javascript:void(0)">${res.current_page} of ${res.last_page}</a></li>
                        <li class="page-item ${res.next_page_url ? '' : 'disabled'}">
                            <a class="page-link" href="javascript:void(0)" onclick="fetchInventory(${res.current_page + 1})">Next</a>
                        </li>
                    </ul>
                </nav>
            `;
            document.getElementById('inventoryPagination').innerHTML = paginationHtml;
        }

        function selectProductAjax(product) {
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
            const clientName = product.client ? product.client.name : '-';

            productsOutbound.push({
                id: product.id,
                partName: product.part_name,
                partNumber: product.part_number,
                partDescription: product.part_description,
                serialNumber: product.serial_number,
                client: clientName
            });

            localStorage.setItem('productsOutbound', JSON.stringify(productsOutbound));

            fetchInventory(currentPage);
            viewProductsOutbound();
        }

        function viewProductsOutbound() {
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
            let html = '';

            if (productsOutbound.length === 0) {
                html = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="ri-shopping-cart-2-line fs-32 opacity-25"></i>
                            <p class="mt-2 mb-0">No products selected.<br>Search and select from the left list.</p>
                        </td>
                    </tr>
                `;
            } else {
                productsOutbound.forEach((product, index) => {
                    html += `
                        <tr>
                            <td><span class="text-muted fw-medium">${index + 1}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="fs-14 mb-1 text-dark">${product.partName}</h6>
                                        <p class="text-muted mb-0 small">PN: ${product.partNumber}</p>
                                        <p class="text-muted mb-0 small text-truncate" style="max-width: 200px;">Desc: ${product.partDescription || '-'}</p>
                                    </div>
                                </div>
                            </td>
                            <td><code class="text-success font-monospace">${product.serialNumber}</code></td>
                            <td><span class="badge badge-soft-primary px-2">${product.client}</span></td>
                            <td class="text-end">
                                <button class="btn btn-soft-danger btn-icon btn-sm" onclick="deleteProduct('${index}')">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            document.getElementById('qtyProduct').value = productsOutbound.length;
            document.getElementById('productsOutbound').innerHTML = html;
        }

        function deleteProduct(index) {
            const productsOutbound = JSON.parse(localStorage.getItem('productsOutbound')) ?? [];
            productsOutbound.splice(index, 1);

            localStorage.setItem('productsOutbound', JSON.stringify(productsOutbound));

            fetchInventory(currentPage);
            viewProductsOutbound();
        }

        function createOutbound() {
            Swal.fire({
                title: "Are you sure?",
                text: "Create Outbound",
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
                        url: '{{ route('outbound.store') }}',
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
