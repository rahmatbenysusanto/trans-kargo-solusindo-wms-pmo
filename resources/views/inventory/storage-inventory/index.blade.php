@extends('layout.index')
@section('title', 'Storage Inventory')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Storage Inventory</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Inventory</a></li>
                        <li class="breadcrumb-item active">Storage Inventory</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-light-subtle py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Storage Occupancy</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Storage Location</th>
                                    <th class="text-center">Total Unique Items</th>
                                    <th class="text-center">Total Quantity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($storages as $index => $storage)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                            <i class="bx bx-store"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="fs-14 mb-0 text-dark">{{ $storage->storageArea->name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $storage->storageRak->name }} •
                                                        {{ $storage->storageLantai->name }} •
                                                        {{ $storage->name }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info px-3 py-2 fs-12">{{ $storage->total_items }} Items</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success px-3 py-2 fs-12">{{ number_format($storage->total_qty) }} Unit</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('inventory.storageInventory.detail', ['bin_id' => $storage->id]) }}"
                                                class="btn btn-soft-primary btn-sm waves-effect waves-light fw-bold px-3">
                                                <i class="bx bx-show align-middle me-1 fs-16"></i> Detail Items
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bx bx-archive fs-32 opacity-25 d-block mb-2"></i>
                                            No inventory found in any storage.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
