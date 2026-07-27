@extends('layout.index')
@section('title', 'Detail Outbound')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Outbound Detail</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('outbound.index') }}">Outbound</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <div class="avatar-title bg-white-subtle text-white rounded-circle fs-20">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="card-title mb-0 text-white">{{ $outbound->number }}</h5>
                            <span class="badge bg-white-subtle text-white">Outbound Transaction</span>
                            @if ($outbound->delivery_note_number)
                                <span class="badge bg-white-subtle text-white ms-1">DN: {{ $outbound->delivery_note_number }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-info btn-sm btn-label waves-effect waves-light"
                            data-bs-toggle="modal" data-bs-target="#addProductsModal">
                            <i class="ri-add-box-line label-icon align-middle fs-16 me-2"></i> Tambah Produk
                        </button>
                        <a href="{{ route('outbound.downloadExcel', ['id' => $outbound->id]) }}"
                            class="btn btn-success btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-excel-2-line label-icon align-middle fs-16 me-2"></i> Excel
                        </a>
                        <a href="{{ route('outbound.downloadPDF', ['id' => $outbound->id]) }}" target="_blank"
                            class="btn btn-danger btn-sm btn-label waves-effect waves-light">
                            <i class="ri-file-pdf-line label-icon align-middle fs-16 me-2"></i> PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Transaction Overview -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-user-star-line text-primary fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Client Information</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $outbound->client->name }}</h5>
                                <p class="text-muted mb-0 small">Registered Stakeholder</p>
                            </div>
                        </div>

                        <!-- Logistics Details -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-truck-line text-info fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Logistics Info</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $outbound->courier }}</h5>
                                <p class="text-muted mb-0 small">AWB: <span
                                        class="text-dark fw-medium">{{ $outbound->tracking_number }}</span></p>
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-map-pin-line text-success fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Destination</h6>
                                </div>
                                <h5 class="fs-15 mb-1">{{ $outbound->site_location }}</h5>
                                <p class="text-muted mb-0 small">Received by: <span
                                        class="text-dark fw-medium">{{ $outbound->received_by }}</span></p>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="col-md-3">
                            <div class="p-3 border border-dashed rounded bg-light-subtle">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ri-calendar-event-line text-warning fs-20 me-2"></i>
                                    <h6 class="mb-0 text-uppercase small fw-bold text-muted">Processing Info</h6>
                                </div>
                                <h5 class="fs-15 mb-1">
                                    {{ \Carbon\Carbon::parse($outbound->delivery_date)->format('d M Y') }}</h5>
                                <p class="text-muted mb-0 small">Handled by: <span
                                        class="text-dark fw-medium">{{ $outbound->user->name }}</span></p>
                            </div>
                        </div>
                    </div>

                    @if ($outbound->remarks)
                        <div class="mt-4 p-3 bg-light rounded border-start border-primary border-3">
                            <div class="d-flex align-items-center">
                                <i class="ri-information-line text-primary fs-18 me-2"></i>
                                <span class="text-muted fw-medium small text-uppercase">Special Remarks:</span>
                            </div>
                            <p class="mb-0 mt-1 text-dark">{{ $outbound->remarks }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-box-3-line text-primary fs-20 me-2"></i>
                        <h5 class="card-title mb-0">Attached Product List</h5>
                        <span class="badge badge-soft-info ms-2">{{ count($outboundDetail) }} Items</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 80px;">#</th>
                                    <th>Product Description</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outboundDetail as $index => $detail)
                                    <tr>
                                        <td><span class="text-muted fw-medium">{{ $index + 1 }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                            <i class="ri-box-3-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0">{{ $detail->inventory->part_name }}</h6>
                                                    <p class="text-muted small mb-0">{{ $detail->inventory->part_description }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="text-muted">{{ $detail->inventory->part_number }}</span></td>
                                        <td><code
                                                class="text-primary font-monospace">{{ $detail->inventory->serial_number }}</code>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-success px-3 py-2">Shipped</span>
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

{{-- ===== MODAL TAMBAH PRODUK ===== --}}
<div class="modal fade" id="addProductsModal" tabindex="-1" aria-labelledby="addProductsModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-light-subtle">
                <div class="d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="ri-add-box-line fs-18"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title" id="addProductsModalLabel">Tambah Produk ke {{ $outbound->number }}</h5>
                        <small class="text-muted">Pilih produk dari inventory untuk ditambahkan ke outbound ini</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    {{-- LEFT: Inventory Search --}}
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="ri-list-unordered fs-18 text-info me-2"></i>
                                    <h6 class="card-title mb-0">Inventory Tersedia</h6>
                                </div>
                            </div>
                            <div class="card-body p-0 d-flex flex-column">
                                <div class="p-3 bg-light border-bottom">
                                    <div class="search-box">
                                        <input type="text" class="form-control" id="modalSearchInventory"
                                            placeholder="Cari Part Name, PN, atau SN...">
                                    </div>
                                </div>
                                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                    <table class="table table-hover table-nowrap align-middle mb-0">
                                        <thead class="bg-light text-muted sticky-top">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Product Information</th>
                                                <th>Serial Number</th>
                                                <th>Client</th>
                                                <th class="text-end" style="width: 80px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalListProducts"></tbody>
                                    </table>
                                </div>
                                <div class="mt-auto p-2 bg-light border-top" id="modalInventoryPagination"></div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Selected Products --}}
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="ri-checkbox-circle-line fs-18 text-success me-2"></i>
                                    <h6 class="card-title mb-0 text-success">Produk Dipilih</h6>
                                    <span class="badge badge-soft-info ms-2" id="modalSelectedCount">0</span>
                                </div>
                            </div>
                            <div class="card-body p-0 d-flex flex-column">
                                {{-- BULK SN INPUT --}}
                                <div class="p-3 border-bottom bg-warning-subtle" id="modalBulkSnSection">
                                    <div class="d-flex align-items-center mb-0">
                                        <div class="d-flex align-items-center flex-shrink-0">
                                            <i class="ri-barcode-line fs-16 me-2 text-warning"></i>
                                            <h6 class="mb-0 fw-bold" style="cursor:pointer" onclick="modalToggleBulkSn()">Bulk SN Input</h6>
                                        </div>
                                        <div class="ms-auto d-flex align-items-center gap-2">
                                            <small class="text-muted" id="modalBulkSnCount">0 SN</small>
                                            <button class="btn btn-sm btn-link p-0 text-muted" type="button" onclick="modalToggleBulkSn()">
                                                <i class="ri-arrow-up-s-line fs-18" id="modalBulkSnToggleIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="modalBulkSnContent" class="mt-2">
                                        <textarea class="form-control form-control-sm font-monospace" id="modalBulkSnTextarea" rows="3"
                                            placeholder="Paste serial numbers (one per line)&#10;SN-00123&#10;SN-00124"></textarea>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div>
                                                <button class="btn btn-sm btn-soft-info me-1" onclick="modalClearBulkSn()">
                                                    <i class="ri-eraser-line"></i> Clear
                                                </button>
                                                <span id="modalBulkSnDuplicateInfo" class="small text-muted" style="display:none;"></span>
                                            </div>
                                            <button class="btn btn-success btn-sm" onclick="modalProcessBulkSn(this)">
                                                <i class="ri-add-line me-1"></i> Add to List
                                            </button>
                                        </div>
                                        <div id="modalBulkSnResult" class="mt-2" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-hover table-nowrap align-middle mb-0">
                                        <thead class="bg-light text-muted text-success sticky-top">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Product Information</th>
                                                <th>Serial Number</th>
                                                <th>Client</th>
                                                <th class="text-end text-danger" style="width: 80px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalSelectedProducts">
                                            <tr id="modalEmptyRow">
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="ri-shopping-cart-2-line fs-32 opacity-25"></i>
                                                    <p class="mt-2 mb-0">Belum ada produk dipilih.<br>Cari dan pilih dari kolom sebelah kiri.</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-primary btn-label waves-effect waves-light" onclick="modalSubmitProducts()">
                    <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Tambahkan ke Outbound
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // ===== MODAL ADD PRODUCTS =====
    let modalSelectedProducts = [];
    let modalCurrentPage = 1;
    let modalSearchQuery = '';

    // Open modal event — reset state
    $('#addProductsModal').on('show.bs.modal', function() {
        modalSelectedProducts = [];
        modalCurrentPage = 1;
        modalSearchQuery = '';
        $('#modalSearchInventory').val('');
        modalFetchInventory();
        modalRenderSelected();
    });

    // Search on keyup
    $('#modalSearchInventory').on('keyup', function() {
        modalSearchQuery = $(this).val();
        modalCurrentPage = 1;
        modalFetchInventory();
    });

    // Bulk SN textarea input
    $('#modalBulkSnTextarea').on('input', function() {
        const sns = modalGetSerialNumbersFromTextarea();
        $('#modalBulkSnCount').text(sns.length + ' SN');
        const unique = new Set(sns);
        if (unique.size < sns.length) {
            $('#modalBulkSnDuplicateInfo').text(sns.length - unique.size + ' duplicate(s) found').show();
        } else {
            $('#modalBulkSnDuplicateInfo').hide();
        }
    });

    function modalFetchInventory(page) {
        if (page) modalCurrentPage = page;
        $.ajax({
            url: '{{ route('outbound.inventory.search') }}',
            method: 'GET',
            data: { search: modalSearchQuery, page: modalCurrentPage },
            success: function(res) {
                modalRenderInventory(res);
            }
        });
    }

    function modalRenderInventory(res) {
        const selectedIds = modalSelectedProducts.map(p => p.id);
        const existingIds = [{{ $outboundDetail->pluck('inventory_id')->join(',') }}];
        let html = '';
        res.data.forEach((product, index) => {
            const isSelected = selectedIds.includes(product.id);
            const isExisting = existingIds.includes(product.id);
            const clientName = product.client ? product.client.name : '-';
            if (isExisting) return;

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
                        ${isSelected
                            ? `<span class="badge badge-soft-success">Selected</span>`
                            : `<button class="btn btn-soft-info btn-icon btn-sm" onclick="modalSelectProduct(${JSON.stringify({id: product.id, part_name: product.part_name, part_number: product.part_number, part_description: product.part_description, serial_number: product.serial_number, client: product.client}).replace(/"/g, '&quot;')})">
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

        $('#modalListProducts').html(html);

        // Pagination
        let paginationHtml = `
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm justify-content-end mb-0">
                    <li class="page-item ${res.prev_page_url ? '' : 'disabled'}">
                        <a class="page-link" href="javascript:void(0)" onclick="modalFetchInventory(${res.current_page - 1})">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="javascript:void(0)">${res.current_page} of ${res.last_page}</a></li>
                    <li class="page-item ${res.next_page_url ? '' : 'disabled'}">
                        <a class="page-link" href="javascript:void(0)" onclick="modalFetchInventory(${res.current_page + 1})">Next</a>
                    </li>
                </ul>
            </nav>
        `;
        $('#modalInventoryPagination').html(paginationHtml);
    }

    function modalSelectProduct(product) {
        const clientName = product.client ? product.client.name : '-';
        modalSelectedProducts.push({
            id: product.id,
            partName: product.part_name,
            partNumber: product.part_number,
            partDescription: product.part_description,
            serialNumber: product.serial_number,
            client: clientName
        });
        modalFetchInventory(modalCurrentPage);
        modalRenderSelected();
    }

    function modalRenderSelected() {
        let html = '';
        if (modalSelectedProducts.length === 0) {
            html = `
                <tr id="modalEmptyRow">
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="ri-shopping-cart-2-line fs-32 opacity-25"></i>
                        <p class="mt-2 mb-0">Belum ada produk dipilih.<br>Cari dan pilih dari kolom sebelah kiri.</p>
                    </td>
                </tr>
            `;
        } else {
            modalSelectedProducts.forEach((product, index) => {
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
                            <button class="btn btn-soft-danger btn-icon btn-sm" onclick="modalRemoveProduct(${index})">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }
        $('#modalSelectedProducts').html(html);
        $('#modalSelectedCount').text(modalSelectedProducts.length);
    }

    function modalRemoveProduct(index) {
        modalSelectedProducts.splice(index, 1);
        modalFetchInventory(modalCurrentPage);
        modalRenderSelected();
    }

    // ===== BULK SN =====
    function modalGetSerialNumbersFromTextarea() {
        const text = document.getElementById('modalBulkSnTextarea').value;
        if (!text.trim()) return [];
        return text.split('\n').map(s => s.trim()).filter(s => s.length > 0);
    }

    function modalToggleBulkSn() {
        const content = document.getElementById('modalBulkSnContent');
        const icon = document.getElementById('modalBulkSnToggleIcon');
        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.className = 'ri-arrow-up-s-line fs-18';
        } else {
            content.style.display = 'none';
            icon.className = 'ri-arrow-down-s-line fs-18';
        }
    }

    function modalClearBulkSn() {
        document.getElementById('modalBulkSnTextarea').value = '';
        $('#modalBulkSnCount').text('0 SN');
        $('#modalBulkSnDuplicateInfo').hide();
        $('#modalBulkSnResult').hide();
    }

    function modalProcessBulkSn(btn) {
        const sns = modalGetSerialNumbersFromTextarea();
        if (sns.length === 0) {
            Swal.fire('Error', 'Please enter at least 1 Serial Number', 'error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

        const existingOutboundIds = [{{ $outboundDetail->pluck('inventory_id')->join(',') }}];
        const selectedIds = modalSelectedProducts.map(p => p.id);

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
                let addedCount = 0;
                let skippedCount = 0;

                found.forEach(product => {
                    if (existingOutboundIds.includes(product.id) || selectedIds.includes(product.id)) {
                        skippedCount++;
                        return;
                    }
                    const clientName = product.client ? product.client.name : '-';
                    modalSelectedProducts.push({
                        id: product.id,
                        partName: product.part_name,
                        partNumber: product.part_number,
                        partDescription: product.part_description,
                        serialNumber: product.serial_number,
                        client: clientName
                    });
                    addedCount++;
                });

                modalFetchInventory(modalCurrentPage);
                modalRenderSelected();

                let resultHtml = '';
                if (addedCount > 0) {
                    resultHtml += `<div class="alert alert-success py-2 mb-1 small"><i class="ri-check-line me-1"></i>${addedCount} SN(s) ditemukan & ditambahkan</div>`;
                }
                if (skippedCount > 0) {
                    resultHtml += `<div class="alert alert-info py-2 mb-1 small"><i class="ri-information-line me-1"></i>${skippedCount} SN(s) sudah ada</div>`;
                }
                if (notFound.length > 0) {
                    resultHtml += `<div class="alert alert-warning py-2 mb-1 small"><i class="ri-error-warning-line me-1"></i>Tidak ditemukan: <strong>${notFound.join(', ')}</strong></div>`;
                }

                const resultDiv = document.getElementById('modalBulkSnResult');
                resultDiv.innerHTML = resultHtml;
                resultDiv.style.display = 'block';

                if (notFound.length === 0) {
                    document.getElementById('modalBulkSnTextarea').value = '';
                    $('#modalBulkSnCount').text('0 SN');
                    $('#modalBulkSnDuplicateInfo').hide();
                }

                setTimeout(() => { resultDiv.style.display = 'none'; }, 8000);
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

    // ===== SUBMIT =====
    function modalSubmitProducts() {
        if (modalSelectedProducts.length === 0) {
            Swal.fire('Error', 'Pilih minimal 1 produk terlebih dahulu', 'error');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: `Tambahkan ${modalSelectedProducts.length} produk ke outbound ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tambahkan!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                cancelButton: 'btn btn-danger w-xs mt-2'
            },
            buttonsStyling: false,
            showCloseButton: true
        }).then((t) => {
            if (t.value) {
                $.ajax({
                    url: '{{ route('outbound.addProducts') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        outbound_id: {{ $outbound->id }},
                        products: modalSelectedProducts
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.message || 'Produk berhasil ditambahkan',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Gagal menambahkan produk', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
