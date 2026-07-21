@extends('admin.layouts.app')
@section('title', 'Cancellation Report')
@section('page-title', 'Cancellation Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Cancellations</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#b71c1c;"></i> Filters</span>
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
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Cancelled By</label>
                <select id="cancelled_by" class="form-control form-control-sm">
                    <option value="">All</option>
                    <option value="counselor">Counselor</option>
                    <option value="counselee">Counsellee</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Counselor</label>
                <select id="counselor_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($counselors as $c)
                    <option value="{{ $c->id }}">{{ $c->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Counselling Area</label>
                <select id="counsel_type_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($counselTypes as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
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
        <span style="font-weight:600;"><i class="fas fa-calendar-times mr-2"></i> Cancelled Appointments</span>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Original Date</th>
                    <th>Counsellee</th>
                    <th>Counselor</th>
                    <th>Counselling Area</th>
                    <th>Cancelled By</th>
                    <th>Cancelled At</th>
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
        ajaxUrl: '{{ route('admin.reports.cancellations.data') }}',
        exportUrl: '{{ route('admin.reports.cancellations.export') }}',
        filterIds: ['date_from', 'date_to', 'cancelled_by', 'counselor_id', 'counsel_type_id'],
        columns: [
            { data: 'appointment_date' },
            { data: 'counselee' },
            { data: 'counselor' },
            { data: 'counsel_type' },
            { data: 'cancelled_by' },
            { data: 'cancelled_at' },
        ],
        order: [],
    });
});
</script>
@endpush
