@extends('layout.index')
@section('title', 'Change Storage Product')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Internal Transfer (Stock Movement)</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item"><a>Movement</a></li>
                        <li class="breadcrumb-item active">New Transfer</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Selection Card -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light-subtle py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="ri-search-eye-line text-info fs-20 me-2"></i>
                        <h5 class="card-title mb-0">Select Items to Move</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted"><i
                                    class="ri-search-2-line"></i></span>
                            <input type="text" class="form-control border-light bg-light" id="searchAsset"
                                placeholder="Search by PN, Name, or SN ..." onkeyup="filterAssets(this.value)">
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted sticky-top">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Asset Details</th>
                                    <th>Current Storage</th>
                                    <th class="text-end">Select</th>
                                </tr>
                            </thead>
                            <tbody id="listAssets">
                                <!-- Rendered via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Target Storage Card -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100 border-start border-info border-3">
                <div class="card-header bg-light-subtle py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-map-pin-range-line text-info fs-20 me-2"></i>
                        <h5 class="card-title mb-0">Destination Storage</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-muted small text-uppercase">Area</label>
                            <select class="form-select border-light bg-light" id="area"
                                onchange="changeStorageArea(this.value)">
                                <option value="">Select Area</option>
                                @foreach ($storageArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small text-uppercase">Rak</label>
                            <select class="form-select border-light bg-light" id="rak"
                                onchange="changeStorageRak(this.value)">
                                <option value="">Select Rak</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small text-uppercase">Lantai</label>
                            <select class="form-select border-light bg-light" id="lantai"
                                onchange="changeStorageLantai(this.value)">
                                <option value="">Select Lantai</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small text-uppercase">Bin / Box</label>
                            <select class="form-select border-light bg-light" id="bin">
                                <option value="">Select Bin</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="card-title fs-13 mb-0 text-uppercase text-muted">Selected Assets (<span
                                id="countSelected">0</span>)</h6>
                        <button class="btn btn-info btn-sm btn-label waves-effect waves-light" onclick="processTransfer()">
                            <i class="ri-arrow-left-right-line label-icon align-middle fs-16 me-2"></i> Process Transfer
                        </button>
                    </div>

                    <div id="selectedAssetList" class="bg-light p-2 rounded"
                        style="min-height: 100px; max-height: 300px; overflow-y: auto;">
                        <div class="text-center py-4 text-muted" id="placeholderSelected">
                            <i class="ri-box-3-line fs-24 d-block mb-1 opacity-25"></i>
                            <small>No items selected for transfer</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let allInventory = @json($inventory);
        let selectedItems = [];
        let searchQuery = '';

        // Initialize display
        renderAssets();

        function filterAssets(val) {
            searchQuery = val.toLowerCase();
            renderAssets();
        }

        function renderAssets() {
            let html = '';
            let filtered = allInventory.filter(item => {
                const matchesSearch = item.part_name.toLowerCase().includes(searchQuery) ||
                    item.part_number.toLowerCase().includes(searchQuery) ||
                    item.serial_number.toLowerCase().includes(searchQuery);
                const isNotSelected = !selectedItems.find(s => s.id === item.id);
                return matchesSearch && isNotSelected;
            });

            filtered.forEach((item, index) => {
                const loc = item.bin ? `${item.bin.storage_area.name} > ${item.bin.name}` : '-';
                html += `
                    <tr>
                        <td class="text-muted small">${index + 1}</td>
                        <td>
                            <div class="fw-medium text-dark">${item.part_name}</div>
                            <div class="small text-muted">SN: <span class="text-primary font-monospace">${item.serial_number}</span></div>
                        </td>
                        <td>
                            <div class="small fw-medium">${loc}</div>
                            <div class="text-muted small" style="font-size: 11px;">${item.inbound_detail.inbound.client.name}</div>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-soft-info btn-icon btn-sm" onclick="selectItem(${item.id})">
                                <i class="ri-add-line"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            if (filtered.length === 0) {
                html = `<tr><td colspan="4" class="text-center py-4 text-muted">No available assets found.</td></tr>`;
            }

            document.getElementById('listAssets').innerHTML = html;
        }

        function selectItem(id) {
            const item = allInventory.find(i => i.id === id);
            selectedItems.push(item);
            renderAssets();
            renderSelected();
        }

        function removeItem(id) {
            selectedItems = selectedItems.filter(i => i.id !== id);
            renderAssets();
            renderSelected();
        }

        function renderSelected() {
            const container = document.getElementById('selectedAssetList');
            const count = document.getElementById('countSelected');
            const placeholder = document.getElementById('placeholderSelected');

            count.innerText = selectedItems.length;

            if (selectedItems.length === 0) {
                placeholder.classList.remove('d-none');
                container.innerHTML = '';
                container.appendChild(placeholder);
                return;
            }

            placeholder.classList.add('d-none');
            let html = '';
            selectedItems.forEach(item => {
                html += `
                    <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border mb-2 shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <div class="avatar-title bg-info-subtle text-info rounded">
                                    <i class="ri-box-3-line"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fs-12 fw-medium text-dark">${item.part_name}</div>
                                <div class="text-muted" style="font-size: 10px;">SN: ${item.serial_number}</div>
                            </div>
                        </div>
                        <button class="btn btn-link text-danger p-0" onclick="removeItem(${item.id})">
                            <i class="ri-close-circle-fill fs-18"></i>
                        </button>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        function processTransfer() {
            const binId = document.getElementById('bin').value;

            if (!binId) {
                Swal.fire('Error', 'Please select destination Storage (Bin).', 'error');
                return;
            }

            if (selectedItems.length === 0) {
                Swal.fire('Error', 'Please select at least one item to move.', 'error');
                return;
            }

            Swal.fire({
                title: "Confirm Transfer?",
                text: `Moving ${selectedItems.length} items to the selected storage bin.`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Transfer Now",
                customClass: {
                    confirmButton: "btn btn-info w-xs me-2 mt-2",
                    cancelButton: "btn btn-danger w-xs mt-2"
                },
                buttonsStyling: false
            }).then(async (t) => {
                if (t.value) {
                    $.ajax({
                        url: '{{ route('inventory.stockMovement.store') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            bin: binId,
                            products: selectedItems
                        },
                        success: (res) => {
                            if (res.status) {
                                Swal.fire('Success', 'Inventory moved successfully.', 'success')
                                    .then(() => {
                                        window.location.href =
                                            '{{ route('inventory.stockMovement.index') }}';
                                    });
                            } else {
                                Swal.fire('Error', res.message || 'Transfer failed.', 'error');
                            }
                        }
                    });
                }
            });
        }

        // Storage selection logic (AJAX)
        function changeStorageArea(areaId) {
            if (!areaId) return;
            $.get('{{ route('storage.rak.find') }}', {
                areaId
            }, function(res) {
                let html = '<option value="">Select Rak</option>';
                res.data.forEach(r => html += `<option value="${r.id}">${r.name}</option>`);
                document.getElementById('rak').innerHTML = html;
                document.getElementById('lantai').innerHTML = '<option value="">Select Lantai</option>';
                document.getElementById('bin').innerHTML = '<option value="">Select Bin</option>';
            });
        }

        function changeStorageRak(rakId) {
            const areaId = document.getElementById('area').value;
            if (!rakId) return;
            $.get('{{ route('storage.lantai.find') }}', {
                areaId,
                rakId
            }, function(res) {
                let html = '<option value="">Select Lantai</option>';
                res.data.forEach(l => html += `<option value="${l.id}">${l.name}</option>`);
                document.getElementById('lantai').innerHTML = html;
                document.getElementById('bin').innerHTML = '<option value="">Select Bin</option>';
            });
        }

        function changeStorageLantai(lantaiId) {
            if (!lantaiId) return;
            $.get('{{ route('storage.bin.find') }}', {
                lantaiId
            }, function(res) {
                let html = '<option value="">Select Bin</option>';
                res.data.forEach(b => html += `<option value="${b.id}">${b.name}</option>`);
                document.getElementById('bin').innerHTML = html;
            });
        }
    </script>
@endsection
