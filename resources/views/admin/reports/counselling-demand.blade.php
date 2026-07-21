@extends('admin.layouts.app')
@section('title', 'Counselling Area Demand Report')
@section('page-title', 'Counselling Area Demand Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Counselling Area Demand</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#6a1b9a;"></i> Filters</span>
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
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Counsellee State</label>
                <select id="state_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($states as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
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
        <span style="font-weight:600;"><i class="fas fa-chart-pie mr-2"></i> Demand vs. Coverage</span>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Counselling Area</th>
                    <th>Bookings</th>
                    <th>Counselors Offering</th>
                    <th>Bookings per Counselor</th>
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
        ajaxUrl: '{{ route('admin.reports.counselling-demand.data') }}',
        exportUrl: '{{ route('admin.reports.counselling-demand.export') }}',
        filterIds: ['date_from', 'date_to', 'state_id', 'counsel_type_id'],
        columns: [
            { data: 'counsel_type' },
            { data: 'booking_count' },
            { data: 'counselor_count' },
            { data: 'ratio' },
        ],
        order: [[1, 'desc']],
    });
});
</script>
@endpush
