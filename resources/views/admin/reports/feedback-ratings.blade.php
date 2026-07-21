@extends('admin.layouts.app')
@section('title', 'Feedback & Ratings Report')
@section('page-title', 'Feedback & Ratings Report')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Feedback & Ratings</li>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-header" style="background:#fff;">
        <span style="font-weight:600;"><i class="fas fa-filter mr-2" style="color:#f9a825;"></i> Filters</span>
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
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Counselor</label>
                <select id="counselor_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($counselors as $c)
                    <option value="{{ $c->id }}">{{ $c->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Counselling Area</label>
                <select id="counsel_type_id" class="form-control form-control-sm">
                    <option value="">All</option>
                    @foreach($counselTypes as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Rating ≤</label>
                <select id="rating" class="form-control form-control-sm">
                    <option value="">Any</option>
                    <option value="1">1 star</option>
                    <option value="2">2 stars or less</option>
                    <option value="3">3 stars or less</option>
                    <option value="4">4 stars or less</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label" style="font-size:12px; font-weight:600; color:#555;">Has Comments</label>
                <select id="has_comments" class="form-control form-control-sm">
                    <option value="">Any</option>
                    <option value="1">With comments</option>
                    <option value="0">Without comments</option>
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
        <span style="font-weight:600;"><i class="fas fa-star mr-2"></i> Feedback</span>
    </div>
    <div class="card-body">
        <table id="reportTable" class="table table-hover" style="width:100%;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Counsellee</th>
                    <th>Counselor</th>
                    <th>Counselling Area</th>
                    <th>Rating</th>
                    <th>Comments</th>
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
        ajaxUrl: '{{ route('admin.reports.feedback-ratings.data') }}',
        exportUrl: '{{ route('admin.reports.feedback-ratings.export') }}',
        filterIds: ['date_from', 'date_to', 'counselor_id', 'counsel_type_id', 'rating', 'has_comments'],
        columns: [
            { data: 'date' },
            { data: 'counselee' },
            { data: 'counselor' },
            { data: 'counsel_type' },
            { data: 'rating' },
            { data: 'comments' },
        ],
        order: [],
    });
});
</script>
@endpush
