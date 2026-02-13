@extends('layout.index')
@section('title', 'Register New System Personnel')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Register New Personnel</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item"><a>User</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-10 col-lg-12 mx-auto">
            <div class="card shadow-sm border-0 border-top border-primary border-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold">Identification & Access Profile</h5>
                    <p class="text-muted mb-0 small text-uppercase">Provide core account details and initial access status
                    </p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Account Username</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-user-circle"></i></span>
                                    <input type="text" class="form-control border-light bg-light fw-medium"
                                        name="username" placeholder="e.g. jdoe_admin" required>
                                </div>
                                <small class="text-muted d-block mt-1">Unique identifier used for authentication.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Display Name</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-id-card"></i></span>
                                    <input type="text" class="form-control border-light bg-light fw-medium"
                                        name="name" placeholder="e.g. John Doe" required>
                                </div>
                                <small class="text-muted d-block mt-1">Preferably full legal name for system audit.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Verified Email
                                    Address</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-envelope"></i></span>
                                    <input type="email" class="form-control border-light bg-light fw-medium"
                                        name="email" placeholder="john.doe@transkargo.com" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Phone Number
                                    (WhatsApp)</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-phone"></i></span>
                                    <input type="number" class="form-control border-light bg-light fw-medium"
                                        name="no_hp" placeholder="08XXXXXXXXXX" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Secure Password</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-lock-alt"></i></span>
                                    <input type="password" class="form-control border-light bg-light fw-medium"
                                        name="password" placeholder="••••••••" required>
                                </div>
                                <small class="text-muted d-block mt-1">Initial temporary password for first login.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Initial Operational
                                    Status</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-toggle-right"></i></span>
                                    <select class="form-select border-light bg-light fw-bold text-primary" name="status">
                                        <option value="active" selected>ACTIVE - Full Immediate Access</option>
                                        <option value="inactive">INACTIVE - Restricted Access</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-2 border-top">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('user.index') }}" class="btn btn-light px-4 fw-medium">Discard
                                        Changes</a>
                                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                                        <i class="bx bx-check-double me-1 fs-16 align-middle"></i> Initialize Personnel
                                        Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
