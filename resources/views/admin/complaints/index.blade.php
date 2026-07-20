@extends('admin.layouts.app')
@section('title', 'Complaints')
@section('page-title', 'Complaints')
@section('breadcrumb')
    <li class="breadcrumb-item active">Complaints</li>
@endsection

@section('content')


<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselee">
            <h3>{{ $counts['total'] }}</h3><p>Total Complaints</p>
            <i class="fas fa-exclamation-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-pending">
            <h3>{{ $counts['open'] + $counts['in_review'] }}</h3><p>Needs Review</p>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-active">
            <h3>{{ $counts['resolved'] }}</h3><p>Resolved</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-closed">
            <h3>{{ $counts['closed'] }}</h3><p>Closed</p>
            <i class="fas fa-box-archive stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-exclamation-circle mr-2"></i> Complaint List
        </span>
    </div>
    <div class="card-body">

        <div class="">
            <table id="complaintsTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Reference</th>
                        <th>Filed By</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Filed On</th>
                        <th class="text-center" width="80">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($complaints as $i => $c)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>
                        <code style="background:#f4f6f9; padding:2px 8px; border-radius:4px; font-size:12px; color:#555;">
                            {{ $c->reference_number }}
                        </code>
                    </td>
                    <td>
                        <span style="text-transform:capitalize; font-weight:600;">{{ $c->filed_by }}</span><br>
                        <span style="font-size:12px; color:#9e9e9e;">
                            {{ $c->filed_by === 'counselor' ? $c->counselor?->full_name : $c->counselee?->full_name }}
                        </span>
                    </td>
                    <td style="font-size:13px; max-width:220px;">{{ Str::limit($c->subject, 50) }}</td>
                    <td>
                        @php
                        $badgeColor = ['open'=>'badge-pending','in_review'=>'badge-pending','resolved'=>'badge-active','closed'=>'badge-inactive'][$c->status] ?? 'badge-pending';
                        @endphp
                        <span class="{{ $badgeColor }}">{{ ucfirst(str_replace('_',' ',$c->status)) }}</span>
                    </td>
                    <td style="font-size:12px; color:#aaa;">{{ $c->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.complaints.show', $c) }}"
                               class="btn-action" title="View" style="background:#eaf7f5; color:#087a7f;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-check-circle" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-0">No complaints filed.</p>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#complaintsTable').DataTable({
        responsive: true,
		autoWidth:false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[5, 'desc']],
        language: {
            search: '',
            searchPlaceholder: 'Search complaints...',
            info: 'Showing _START_ to _END_ of _TOTAL_ complaints',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last:  '<i class="fas fa-angle-double-right"></i>',
                next:  '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
            }
        },
        columnDefs: [{ orderable: false, targets: [-1] }]
    });
});
</script>
@endpush
