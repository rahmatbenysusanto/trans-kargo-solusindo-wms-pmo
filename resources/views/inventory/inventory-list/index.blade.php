@extends('layout.index')
@section('title', 'Inventory List')

@section('content')
    <style>
        /* Styling for the QR label tag */
        .qr-label-card {
            width: 110px;
            background: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 6px;
            padding: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .qr-label-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #405189;
        }

        .qr-label-card::after {
            content: '\ea2e'; /* Boxicon zoom-in or boxicon details icon */
            font-family: 'boxicons';
            position: absolute;
            top: 4px;
            right: 4px;
            font-size: 11px;
            color: #adb5bd;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .qr-label-card:hover::after {
            opacity: 1;
        }

        .qr-code-placeholder {
            background: #fafafa;
            border-radius: 4px;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code-placeholder canvas,
        .qr-code-placeholder img {
            width: 64px !important;
            height: 64px !important;
        }

    </style>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Inventory Stock</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Stock List</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Part Name</label>
                                <input type="text" class="form-control border-light bg-light" name="partName"
                                    value="{{ request()->get('partName') }}" placeholder="Search Name ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Part Number</label>
                                <input type="text" class="form-control border-light bg-light" name="partNumber"
                                    value="{{ request()->get('partNumber') }}" placeholder="Search PN ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Serial Number</label>
                                <input type="text" class="form-control border-light bg-light" name="serialNumber"
                                    value="{{ request()->get('serialNumber') }}" placeholder="Search SN ...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Client</label>
                                <select class="form-select border-light bg-light" name="client">
                                    <option value="">All Clients</option>
                                    @foreach ($client as $item)
                                        <option value="{{ $item->id }}"
                                            {{ request()->get('client') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small text-uppercase">Stock Status</label>
                                <select class="form-select border-light bg-light" name="status">
                                    <option value="">All Status</option>
                                    <option value="available"
                                        {{ request()->get('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="reserved" {{ request()->get('status') == 'reserved' ? 'selected' : '' }}>
                                        Reserved</option>
                                    <option value="in use" {{ request()->get('status') == 'in use' ? 'selected' : '' }}>In
                                        Use</option>
                                    <option value="defective"
                                        {{ request()->get('status') == 'defective' ? 'selected' : '' }}>Defective</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">
                                    <i class="bx bx-search align-middle me-1"></i> Search
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-soft-danger px-3">
                                    <i class="bx bx-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div
                    class="card-header bg-light-subtle py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Live Inventory Records</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('inventory.downloadExcel', request()->all()) }}"
                            class="btn btn-soft-success btn-sm btn-label waves-effect waves-light fw-bold">
                            <i class="bx bxs-file-export label-icon align-middle fs-16 me-2"></i> Export Excel
                        </a>
                        <a href="{{ route('inventory.downloadPDF', request()->all()) }}" target="_blank"
                            class="btn btn-soft-danger btn-sm btn-label waves-effect waves-light fw-bold">
                            <i class="bx bxs-file-pdf label-icon align-middle fs-16 me-2"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Asset Details</th>
                                    <th>Storage Location</th>
                                    <th>Serial Number</th>
                                    <th>Ownership</th>
                                    <th class="text-center">Status</th>
                                    <th>Client / Owner</th>
                                    <th>Remark</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventory as $index => $product)
                                    <tr>
                                        <td class="text-muted">{{ $inventory->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-info-subtle text-info rounded">
                                                            <i class="bx bx-package"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 text-dark">{{ $product->part_name }}</h6>
                                                    <div class="text-muted small">PN: {{ $product->part_number }}</div>
                                                    <div class="text-muted small">Desc: {{ $product->part_description }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-map-pin text-muted me-2"></i>
                                                <div>
                                                    <div class="small fw-medium">{{ $product->bin->storageArea->name }}
                                                    </div>
                                                    <div class="text-muted" style="font-size: 11px;">
                                                        {{ $product->bin->storageRak->name }} •
                                                        {{ $product->bin->storageLantai->name }} •
                                                        {{ $product->bin->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code
                                                class="text-primary font-monospace fw-medium">{{ $product->serial_number }}</code>
                                        </td>
                                        <td>
                                            <div class="small fw-medium">
                                                {{ $product->inboundDetail->inbound->owner_status }}</div>
                                            <div class="text-muted" style="font-size: 11px;">PIC:
                                                {{ $product->pic->name ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = 'bg-secondary';
                                                $statusText = ucfirst($product->status);
                                                switch ($product->status) {
                                                    case 'available':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'reserved':
                                                        $statusClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'in use':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'defective':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                            @endphp
                                            <span
                                                class="badge {{ $statusClass }} px-3 py-2 fs-11 text-uppercase">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border">{{ $product->inboundDetail->inbound->client->name }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $product->remark ?: '-' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button type="button"
                                                        class="btn btn-soft-info btn-icon btn-sm btn-edit-desc"
                                                        data-id="{{ $product->id }}"
                                                        data-pn="{{ $product->part_number }}"
                                                        data-sn="{{ $product->serial_number }}"
                                                        data-desc="{{ $product->part_description }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Edit Description">
                                                    <i class="bx bx-edit-alt fs-16"></i>
                                                </button>
                                                <button type="button"
                                                        class="btn btn-soft-primary btn-icon btn-sm btn-view-qr"
                                                        data-pn="{{ $product->part_number }}"
                                                        data-sn="{{ $product->serial_number }}"
                                                        data-bs-toggle="tooltip"
                                                        title="View QR Label">
                                                    <i class="bx bx-qr-scan fs-16"></i>
                                                </button>
                                                <a href="{{ route('inbound.inventory.history', ['id' => $product->id]) }}"
                                                    class="btn btn-soft-secondary btn-icon btn-sm waves-effect waves-light"
                                                    data-bs-toggle="tooltip" title="View History">
                                                    <i class="bx bx-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="bx bx-box fs-32 opacity-25 d-block mb-2"></i>
                                            No assets found matching the search criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted small">
                            Showing {{ $inventory->firstItem() ?? 0 }} to {{ $inventory->lastItem() ?? 0 }} of
                            {{ $inventory->total() }} entries
                        </div>
                        <div>
                            {{ $inventory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Part Description Modal -->
    <div class="modal fade" id="editDescModal" tabindex="-1" aria-labelledby="editDescModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-soft-info border-bottom-0 rounded-top">
                    <h5 class="modal-title fw-bold text-dark" id="editDescModalLabel">
                        <i class="bx bx-edit-alt align-middle me-2 text-info"></i>Edit Part Description
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <input type="hidden" id="edit-desc-id">
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="text-muted small text-uppercase fw-semibold">Part Number</div>
                                <div class="fw-medium text-dark font-monospace" id="edit-desc-pn">-</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted small text-uppercase fw-semibold">Serial Number</div>
                                <div class="fw-medium text-dark font-monospace" id="edit-desc-sn">-</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold text-dark">
                            Part Description <span class="text-muted fw-normal">(max 500 characters)</span>
                        </label>
                        <textarea id="edit-desc-textarea"
                            class="form-control border-2 bg-light-subtle"
                            rows="4"
                            placeholder="Enter part description here..."
                            maxlength="500"
                            style="resize: vertical; min-height: 100px; font-size: 14px;"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Describe the part clearly for easy identification.</small>
                            <small class="text-muted"><span id="edit-desc-char-count">0</span>/500</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light fw-semibold px-4" data-bs-dismiss="modal">
                        <i class="bx bx-x align-middle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-info fw-bold px-4" id="btn-save-desc">
                        <i class="bx bx-check align-middle me-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="qrModalLabel">Asset QR Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-2 pb-4">
                    <p class="text-muted small mb-3">Scan this QR code using a scanner to identify the product.</p>
                    
                    <!-- Label Container that matches print format -->
                    <div class="bg-light p-4 rounded-3 border border-dashed border-2 mb-4 d-inline-block">
                        <div id="modal-label-print-area" class="bg-white p-3 border rounded shadow-sm d-flex flex-column align-items-center" style="width: 220px;">
                            <div id="modal-qr-placeholder" class="mb-3"></div>
                            <div class="text-muted fw-bold text-center w-100 text-truncate" id="modal-pn-text" style="font-size: 11px; border-top: 1px dashed #eee; padding-top: 8px; margin-top: 5px;">
                                PN: -
                            </div>
                            <div class="text-dark font-monospace text-center w-100 text-truncate" id="modal-sn-text" style="font-size: 11px;">
                                SN: -
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary fw-bold" id="btn-print-qr-label">
                            <i class="bx bx-printer align-middle me-1"></i> Print Label
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
            // QR Codes will render dynamically inside the modal when requested via the view button.

            var modalQr = null;

            // ========== Edit Part Description ==========
            var editDescModal = new bootstrap.Modal(document.getElementById('editDescModal'));

            $('.btn-edit-desc').on('click', function() {
                var id = $(this).data('id');
                var pn = $(this).data('pn');
                var sn = $(this).data('sn');
                var currentDesc = $(this).data('desc') || '';

                $('#edit-desc-id').val(id);
                $('#edit-desc-pn').text(pn);
                $('#edit-desc-sn').text(sn);
                $('#edit-desc-textarea').val(currentDesc);
                $('#edit-desc-char-count').text(currentDesc.length);

                editDescModal.show();
            });

            $('#edit-desc-textarea').on('input', function() {
                $('#edit-desc-char-count').text($(this).val().length);
            });

            $('#btn-save-desc').on('click', function() {
                var id = $('#edit-desc-id').val();
                var newDesc = $('#edit-desc-textarea').val().trim();
                var $btn = $(this);

                $btn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin align-middle me-1"></i> Saving...');

                $.ajax({
                    url: '{{ route('inventory.updatePartDescription') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify({
                        id: id,
                        part_description: newDesc
                    })
                }).then(function(res) {
                    if (res.status) {
                        editDescModal.hide();
                        Swal.fire({
                            title: 'Updated!',
                            text: 'Part description has been saved.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: res.message || 'Update failed',
                            icon: 'error'
                        });
                    }
                }).catch(function(err) {
                    Swal.fire({
                        title: 'Error',
                        text: err.responseJSON?.message || 'Request failed. Please try again.',
                        icon: 'error'
                    });
                }).always(function() {
                    $btn.prop('disabled', false).html('<i class="bx bx-check align-middle me-1"></i> Save Changes');
                });
            });

            // Reset char count when modal is closed
            document.getElementById('editDescModal').addEventListener('hidden.bs.modal', function() {
                $('#btn-save-desc').prop('disabled', false).html('<i class="bx bx-check align-middle me-1"></i> Save Changes');
            });
            // ========== End Edit Part Description ==========

            // Handle Click on QR Label Card
            $('.btn-view-qr').on('click', function() {
                var pn = $(this).data('pn');
                var sn = $(this).data('sn');

                $('#modal-pn-text').text('PN: ' + pn);
                $('#modal-sn-text').text('SN: ' + sn);
                $('#modal-qr-placeholder').empty();

                // Generate larger QR code inside modal
                modalQr = new QRCode(document.getElementById('modal-qr-placeholder'), {
                    text: sn.toString(),
                    width: 140,
                    height: 140,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });

                // Show Bootstrap modal
                var qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
                qrModal.show();
            });

            // Robust helper to extract/generate QR code base64 source reliably
            function getQRCodeSrc(pn, sn, callback) {
                // 1. Try to extract directly from the already rendered modal placeholder (already in active DOM)
                var modalQrPlaceholder = document.getElementById('modal-qr-placeholder');
                if (modalQrPlaceholder) {
                    var generatedImg = modalQrPlaceholder.querySelector('img');
                    var generatedCanvas = modalQrPlaceholder.querySelector('canvas');
                    var src = '';
                    
                    if (generatedImg && generatedImg.src && generatedImg.src.indexOf('data:image') === 0 && generatedImg.src !== 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7') {
                        src = generatedImg.src;
                    } else if (generatedCanvas) {
                        src = generatedCanvas.toDataURL();
                    }
                    
                    if (src) {
                        callback(src);
                        return;
                    }
                }
                
                // 2. Fallback: generate it on an attached hidden container so the browser renders it fully
                var tempDiv = document.createElement('div');
                tempDiv.style.position = 'absolute';
                tempDiv.style.left = '-9999px';
                tempDiv.style.top = '-9999px';
                document.body.appendChild(tempDiv);
                
                new QRCode(tempDiv, {
                    text: sn.toString(),
                    width: 120,
                    height: 120,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
                
                // Allow a small tick for QRCode library base64 conversion
                setTimeout(function() {
                    var img = tempDiv.querySelector('img');
                    var canvas = tempDiv.querySelector('canvas');
                    var src = '';
                    
                    if (img && img.src && img.src.indexOf('data:image') === 0 && img.src !== 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7') {
                        src = img.src;
                    } else if (canvas) {
                        src = canvas.toDataURL();
                    }
                    
                    document.body.removeChild(tempDiv);
                    callback(src);
                }, 150);
            }

            // Isolated Sandbox Printing for Barcode Labels (guarantees paper sizing & prevents CSS conflicts)
            function printLabel(pn, sn) {
                getQRCodeSrc(pn, sn, function(qrSrc) {
                    // Create sandboxed print iframe with dimensions matching standard sticker sizes to prevent collapsed (blank) layout
                    var iframe = document.createElement('iframe');
                    iframe.style.position = 'fixed';
                    iframe.style.width = '50mm';
                    iframe.style.height = '30mm';
                    iframe.style.left = '-9999px';
                    iframe.style.top = '-9999px';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);

                    var doc = iframe.contentWindow.document;
                    doc.open();
                    doc.write(`
                        <html>
                        <head>
                            <title>Print Label</title>
                            <style>
                                @page {
                                    size: 50mm 30mm;
                                    margin: 0;
                                }
                                * {
                                    box-sizing: border-box;
                                }
                                body {
                                    margin: 0;
                                    padding: 0;
                                    background: #ffffff;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    width: 50mm;
                                    height: 30mm;
                                }
                                .label-container {
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    text-align: center;
                                    width: 100%;
                                    height: 100%;
                                    padding: 1.5mm;
                                }
                                .qr-code {
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-bottom: 1.2mm;
                                }
                                .qr-code img {
                                    width: 18mm !important;
                                    height: 18mm !important;
                                    display: block !important;
                                }
                                .label-text {
                                    font-family: 'Courier New', Courier, monospace;
                                    font-size: 6.5pt;
                                    font-weight: bold;
                                    line-height: 1.2;
                                    color: #000000;
                                    width: 100%;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    margin: 0;
                                    text-transform: uppercase;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="label-container">
                                <div class="qr-code">
                                    <img src="${qrSrc}" id="qr-img" />
                                </div>
                                <div class="label-text">PN: ${pn}</div>
                                <div class="label-text">SN: ${sn}</div>
                            </div>
                            <script>
                                function triggerPrint() {
                                    window.focus();
                                    setTimeout(function() {
                                        window.print();
                                    }, 100);
                                }
                                
                                var img = document.getElementById('qr-img');
                                if (img && img.complete) {
                                    triggerPrint();
                                } else if (img) {
                                    img.onload = triggerPrint;
                                    img.onerror = triggerPrint;
                                } else {
                                    triggerPrint();
                                }
                                
                                // Auto clean-up iframe after user prints/closes print dialog
                                window.onafterprint = function() {
                                    try {
                                        window.parent.document.body.removeChild(window.frameElement);
                                    } catch (e) {
                                        console.log(e);
                                    }
                                };
                            <\/script>
                        </body>
                        </html>
                    `);
                    doc.close();
                });
            }

            // Handle Print Label Button click
            $('#btn-print-qr-label').on('click', function() {
                var pn = $('#modal-pn-text').text().replace('PN: ', '').trim();
                var sn = $('#modal-sn-text').text().replace('SN: ', '').trim();
                printLabel(pn, sn);
            });
        });
    </script>
@endsection
