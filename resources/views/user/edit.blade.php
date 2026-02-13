@extends('layout.index')
@section('title', 'Modify Personnel Credentials')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 text-uppercase fw-bold text-primary">Modify Personnel Credentials</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a>Warehouse</a></li>
                        <li class="breadcrumb-item"><a>User Control</a></li>
                        <li class="breadcrumb-item active">Modify Profile</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-xl-10 col-lg-12 mx-auto">
            <div class="card shadow-sm border-0 border-top border-info border-3">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                    <div class="avatar-xs me-3">
                        <div class="avatar-title bg-info text-white rounded-circle fw-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Modifying: {{ $user->name }}</h5>
                        <p class="text-muted mb-0 small text-uppercase">Update personnel identification or reset security
                            parameters</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <div class="row g-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Account Username</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-user-circle"></i></span>
                                    <input type="text" class="form-control border-light bg-light fw-medium"
                                        value="{{ $user->username }}" name="username" placeholder="Username ..." required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Display Name</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-id-card"></i></span>
                                    <input type="text" class="form-control border-light bg-light fw-medium"
                                        value="{{ $user->name }}" name="name" placeholder="Name ..." required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Verified Email
                                    Address</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-envelope"></i></span>
                                    <input type="email" class="form-control border-light bg-light fw-medium"
                                        value="{{ $user->email }}" name="email" placeholder="user@mail.com ..." required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Phone Number
                                    (WhatsApp)</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-phone"></i></span>
                                    <input type="number" class="form-control border-light bg-light fw-medium"
                                        value="{{ $user->no_hp }}" name="no_hp" placeholder="089 ..." required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Security Password</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-info"><i
                                            class="bx bx-lock-open-alt"></i></span>
                                    <input type="password" class="form-control border-light bg-light fw-medium"
                                        value="********" name="password" placeholder="Leave unchanged to keep current"
                                        required>
                                </div>
                                <small class="text-info d-block mt-1">Leave as default markers unless requesting a hard
                                    reset.</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Operational Access
                                    Status</label>
                                <div class="input-group shadow-none">
                                    <span class="input-group-text bg-light border-light text-primary"><i
                                            class="bx bx-toggle-right"></i></span>
                                    <select
                                        class="form-select border-light bg-light fw-bold {{ $user->status == 'active' ? 'text-success' : 'text-danger' }}"
                                        name="status"
                                        onchange="this.className=this.options[this.selectedIndex].value=='active'?'form-select border-light bg-light fw-bold text-success':'form-select border-light bg-light fw-bold text-danger'">
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>ACTIVE -
                                            Full Grant</option>
                                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>
                                            INACTIVE - Suspension</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-2 border-top">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('user.index') }}" class="btn btn-light px-4 fw-medium">Cancel
                                        Changes</a>
                                    <button type="submit" class="btn btn-info text-white px-5 fw-bold shadow-sm">
                                        <i class="bx bx-save me-1 fs-16 align-middle"></i> Persist Modifications
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
