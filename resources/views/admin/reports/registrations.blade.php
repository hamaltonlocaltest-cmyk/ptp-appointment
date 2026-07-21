@extends('admin.layouts.app')
@section('title', 'Registration & Growth Report')
@section('page-title', 'Registration & Growth Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Registration & Growth</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#00695c;"></i> Filters</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">From</label>
                <input type="date" id="date_from" class="form-control form-control-sm">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">To</label>
                <input type="date" id="date_to" class="form-control form-control-sm">
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Role</label>
                <select id="role" class="form-control form-control-sm">
                    <option value="">Both</option>
                    <option value="counselor">Counselor</option>
                    <option value="counselee">Counsellee</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Status</label>
                <select id="status" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Country</label>
                <select id="country_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($countries as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">State</label>
                <select id="state_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($states as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-end flex-wrap mt-2 pt-3" style="border-top:1px solid #f0f1f5; gap:8px;">
            <button type="button" id="resetFiltersBtn" class="btn btn-sm btn-light" style="border:1px solid #e0e4ec;">
                <i class="fas fa-redo mr-1"></i> Reset
            </button>
            <button type="button" id="exportCsvBtn" class="btn btn-sm" style="background:#1b5e20; color:#fff;">
                <i class="fas fa-file-csv mr-1"></i> Export CSV
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-user-plus mr-2"></i> Registrations</span>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered On</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-reports.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    initReportTable({
        tableSelector: '#reportTable',
        ajaxUrl: '{{ route('admin.reports.registrations.data') }}',
        exportUrl: '{{ route('admin.reports.registrations.export') }}',
        filterIds: ['date_from', 'date_to', 'role', 'status', 'country_id', 'state_id'],
        columns: [
            { data: 'role' },
            { data: 'name' },
            { data: 'email' },
            { data: 'status' },
            { data: 'registered' },
        ],
        order: [],
    });
});
</script>
@endpush
