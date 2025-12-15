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
                                    <option>-- Choose Client --</option>
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
                                    <option>-- Choose Ownership Status --</option>
                                </select>
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
                            <a class="btn btn-secondary btn-sm">Import Excel</a>
                            <a class="btn btn-primary btn-sm">Add Product</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Part Number</th>
                                    <th>Serial Number</th>
                                    <th>Condition</th>
                                    <th>Manufacture Date</th>
                                    <th>Warranty End Date</th>
                                    <th>EOS Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
