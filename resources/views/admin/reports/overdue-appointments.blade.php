@extends('admin.layouts.app')
@section('title', 'Overdue Appointments Report')
@section('page-title', 'Overdue Appointments Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Overdue Appointments</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#c62828;"></i> Filters</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">From</label>
                <input type="date" id="date_from" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">To</label>
                <input type="date" id="date_to" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Counselor</label>
                <select id="counselor_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($counselors as $c)
                    <option value="{{ $c->id }}">{{ $c->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Status</label>
                <select id="status" class="form-control form-control-sm">
                    <option value="">Pending &amp; Confirmed</option>
                    <option value="pending">Pending only</option>
                    <option value="confirmed">Confirmed only</option>
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
        <span style="font-weight:600;"><i class="fas fa-hourglass-half mr-2"></i> Overdue Appointments</span>
        <small class="text-muted ml-2">— Sessions past their scheduled end time, never marked completed</small>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Counsellee</th>
                    <th>Counselor</th>
                    <th>Counselling Area</th>
                    <th>Status</th>
                    <th>Days Overdue</th>
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
        ajaxUrl: '{{ route('admin.reports.overdue-appointments.data') }}',
        exportUrl: '{{ route('admin.reports.overdue-appointments.export') }}',
        filterIds: ['date_from', 'date_to', 'counselor_id', 'status'],
        columns: [
            { data: 'date' },
            { data: 'time' },
            { data: 'counselee' },
            { data: 'counselor' },
            { data: 'counsel_type' },
            { data: 'status' },
            { data: 'days_overdue' },
        ],
        order: [],
    });
});
</script>
@endpush
