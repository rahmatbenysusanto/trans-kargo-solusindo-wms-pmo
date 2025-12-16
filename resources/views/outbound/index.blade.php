@extends('layout.index')
@section('title', 'Outbound')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Outbound</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active"><a>Outbound</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3">
            <a class="btn btn-secondary">Create Outbound</a>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form>
                        <div class="row">
                            <div class="col-2">
                                <label class="form-label">Client</label>
                                <select class="form-control" name="client">
                                    <option value="">-- Choose Client --</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label class="form-label">Delivery Date</label>
                                <input type="date" class="form-control" name="delivery_date">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Courier</label>
                                <input type="text" class="form-control" name="courier" placeholder="Courier ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">AWB</label>
                                <input type="text" class="form-control" name="awb" placeholder="AWB ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Received By</label>
                                <input type="text" class="form-control" name="awb" placeholder="Received By ...">
                            </div>
                            <div class="col-2">
                                <label class="form-label text-white">-</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ url()->current() }}" class="btn btn-danger">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Site Location</th>
                                    <th>QTY Items</th>
                                    <th>Delivery Date</th>
                                    <th>Courier</th>
                                    <th>AWB</th>
                                    <th>Received By</th>
                                    <th>Processed By</th>
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
