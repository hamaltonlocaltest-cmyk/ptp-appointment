@extends('admin.layouts.app')
@section('title', 'Counselor Performance Report')
@section('page-title', 'Counselor Performance Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Counselor Performance</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#1b5e20;"></i> Filters</span>
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
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Min. Sessions</label>
                <input type="number" id="min_sessions" min="0" class="form-control form-control-sm" placeholder="0">
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
        <span style="font-weight:600;"><i class="fas fa-user-tie mr-2"></i> Counselor Performance</span>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Counselor</th>
                    <th>Total Sessions</th>
                    <th>Completed</th>
                    <th>Cancelled</th>
                    <th>Completion Rate</th>
                    <th>Avg Rating</th>
                    <th>Feedback Count</th>
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
        ajaxUrl: '{{ route('admin.reports.counselor-performance.data') }}',
        exportUrl: '{{ route('admin.reports.counselor-performance.export') }}',
        filterIds: ['date_from', 'date_to', 'counselor_id', 'counsel_type_id', 'min_sessions'],
        columns: [
            { data: 'counselor' },
            { data: 'total_sessions' },
            { data: 'completed_sessions' },
            { data: 'cancelled_sessions' },
            { data: 'completion_rate' },
            { data: 'avg_rating' },
            { data: 'feedback_count' },
        ],
        order: [[1, 'desc']],
    });
});
</script>
@endpush
