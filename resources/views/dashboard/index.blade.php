@extends('layout.index')
@section('title', 'Dashboard Overview')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Operational Overview</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <!-- Inbound Dismantle -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Inbound Dismantle</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success text-white rounded-3 fs-24">
                                            <i class="mdi mdi-download"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h2 class="mb-2 fw-bold counter-value" data-target="{{ $inboundDismantle }}">
                                        {{ number_format($inboundDismantle) }}</h2>
                                    <a href="{{ route('inbound.receiving.index') }}"
                                        class="text-success text-decoration-none fw-medium fs-13">
                                        View Details <i class="ri-arrow-right-line align-middle ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="progress progress-sm rounded-0" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Inbound Relocation -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Inbound Relocation</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info text-white rounded-3 fs-24">
                                            <i class="mdi mdi-swap-horizontal"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h2 class="mb-2 fw-bold counter-value" data-target="{{ $inboundRelocation }}">
                                        {{ number_format($inboundRelocation) }}</h2>
                                    <a href="{{ route('inbound.receiving.index') }}"
                                        class="text-info text-decoration-none fw-medium fs-13">
                                        View Details <i class="ri-arrow-right-line align-middle ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="progress progress-sm rounded-0" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Total Outbound -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Total Outbound</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary text-white rounded-3 fs-24">
                                            <i class="mdi mdi-truck-fast"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h2 class="mb-2 fw-bold counter-value" data-target="{{ $outbound }}">
                                        {{ number_format($outbound) }}</h2>
                                    <a href="{{ route('outbound.index') }}"
                                        class="text-primary text-decoration-none fw-medium fs-13">
                                        View Details <i class="ri-arrow-right-line align-middle ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="progress progress-sm rounded-0" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Total Return -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-animate border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <p class="text-uppercase fw-semibold text-muted mb-0 fs-13">Total Return</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning text-white rounded-3 fs-24">
                                            <i class="mdi mdi-undo-variant"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-3">
                                <div>
                                    <h2 class="mb-2 fw-bold counter-value" data-target="{{ $return }}">
                                        {{ number_format($return) }}</h2>
                                    <a href="{{ route('return.index') }}"
                                        class="text-warning text-decoration-none fw-medium fs-13">
                                        View Details <i class="ri-arrow-right-line align-middle ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="progress progress-sm rounded-0" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"
                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
