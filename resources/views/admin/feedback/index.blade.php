@extends('admin.layouts.app')
@section('title', 'Feedback')
@section('page-title', 'Feedback')
@section('breadcrumb')
    <li class="breadcrumb-item active">Feedback</li>
@endsection

@section('content')


<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselee">
            <h3>{{ $counts['total'] }}</h3><p>Total Feedback</p>
            <i class="fas fa-comment-dots stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-pending">
            <h3>{{ $counts['average'] }}<small style="font-size:16px;">/5</small></h3><p>Average Rating</p>
            <i class="fas fa-star stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-active">
            <h3>{{ $counts['five'] }}</h3><p>5-Star Reviews</p>
            <i class="fas fa-thumbs-up stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-inactive">
            <h3>{{ $counts['low'] }}</h3><p>Low Ratings (&le;2)</p>
            <i class="fas fa-thumbs-down stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-comment-dots mr-2"></i> Feedback List
        </span>
    </div>
    <div class="card-body">

        <div class="">
            <table id="feedbackTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Counselee</th>
                        <th>Counselor</th>
                        <th>Counselling Area</th>
                        <th>Rating</th>
                        <th>Submitted</th>
                        <th class="text-center" width="80">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($feedbacks as $i => $f)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $f->counselee->full_name }}</td>
                    <td>{{ $f->counselor->full_name }}</td>
                    <td>{{ $f->appointment->counselType->name }}</td>
                    <td>
                        <span style="color:#f9a825;">
                            @for($i2=1;$i2<=5;$i2++)<i class="fas fa-star" style="{{ $i2 > $f->rating ? 'color:#e0e4ec;' : '' }}"></i>@endfor
                        </span>
                    </td>
                    <td style="font-size:12px; color:#aaa;">{{ $f->submitted_at?->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.feedback.show', $f) }}"
                               class="btn-action" title="View" style="background:#e3f2fd; color:#1565c0;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-comment-dots" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-0">No feedback submitted yet.</p>
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
    $('#feedbackTable').DataTable({
        responsive: true,
		autoWidth:false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[5, 'desc']],
        language: {
            search: '',
            searchPlaceholder: 'Search feedback...',
            info: 'Showing _START_ to _END_ of _TOTAL_ feedback entries',
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
