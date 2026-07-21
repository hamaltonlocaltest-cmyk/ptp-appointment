@extends('admin.layouts.app')
@section('title', 'City Coverage Gap Report')
@section('page-title', 'City Coverage Gap Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">City Coverage Gap</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#e65100;"></i> Filters</span>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3" style="font-size:12.5px;">
            Cities where counsellees are registered but no active counselor offers in-person sessions there.
        </p>
        <div class="row">
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">State</label>
                <select id="state_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($states as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Country</label>
                <select id="country_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($countries as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Min. Counsellees</label>
                <input type="number" id="min_counselees" min="1" class="form-control form-control-sm" placeholder="1">
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
        <span style="font-weight:600;"><i class="fas fa-map-marked-alt mr-2"></i> Coverage Gaps</span>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Counsellees Without Local Counselor</th>
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
        ajaxUrl: '{{ route('admin.reports.city-coverage-gap.data') }}',
        exportUrl: '{{ route('admin.reports.city-coverage-gap.export') }}',
        filterIds: ['state_id', 'country_id', 'min_counselees'],
        columns: [
            { data: 'city' },
            { data: 'state' },
            { data: 'country' },
            { data: 'counselee_count' },
        ],
        order: [[3, 'desc']],
    });
});
</script>
@endpush
