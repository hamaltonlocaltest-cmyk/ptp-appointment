@extends('admin.layouts.app')
@section('title', 'Donations')
@section('page-title', 'Donations')
@section('breadcrumb')
    <li class="breadcrumb-item active">Donations</li>
@endsection

@section('content')


<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
            <h3>&#8377;{{ number_format((float) $counts['total_received'], 0) }}</h3><p>Total Received</p>
            <i class="fas fa-hand-holding-heart stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
            <h3>{{ $counts['total'] }}</h3><p>Total Donations</p>
            <i class="fas fa-heart stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
            <h3>{{ $counts['pending'] }}</h3><p>Pending</p>
            <i class="fas fa-hourglass-half stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#b71c1c,#c62828);">
            <h3>{{ $counts['failed'] }}</h3><p>Failed</p>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-hand-holding-heart mr-2"></i> Donation List
        </span>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="donationsTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Donor</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Ref</th>
                        <th>Date</th>
                        <th class="text-center" width="110">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($donations as $i => $d)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $d->donor_display_name }}</div>
                        <div style="font-size:12px; color:#9e9e9e;">{{ $d->counselee->email ?? $d->donor_email }}</div>
                    </td>
                    <td style="font-weight:700;">{{ $d->currency }} {{ number_format((float) $d->amount, 2) }}</td>
                    <td>
                        @if($d->status === 'completed')
                            <span class="badge-active">Completed</span>
                        @elseif($d->status === 'failed')
                            <span class="badge-inactive">Failed</span>
                        @else
                            <span class="badge-pending">Pending</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#666;">{{ $d->payment_reference ?: '—' }}</td>
                    <td style="font-size:12px; color:#aaa;">{{ $d->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            <a href="{{ route('admin.donations.show', $d) }}"
                               class="btn-action" title="View" style="background:#e3f2fd; color:#1565c0;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($d->status === 'pending')
                            <form action="{{ route('admin.donations.complete', $d) }}" method="POST" id="complete-form-{{ $d->id }}">
                                @csrf
                                <button type="button" class="btn-action" title="Mark Completed" style="background:#e8f5e9; color:#1b5e20;"
                                        onclick="openCompleteModal('{{ $d->id }}', '{{ $d->currency }} {{ number_format((float) $d->amount, 2) }}', '{{ $d->donor_display_name }}')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-hand-holding-heart" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-0">No donations received yet.</p>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-check" style="font-size:24px; color:#1b5e20;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Mark donation as completed?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:16px;">
                    Use this only after confirming payment was received outside of Instamojo (e.g. bank transfer). The donor will get a receipt email.
                </p>
                <div class="text-left" style="background:#f8f9fc; border-radius:10px; padding:14px 16px; font-size:13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Amount</span>
                        <strong id="completeAmount" style="color:#1a1a2e;"></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Donor</span>
                        <strong id="completeDonor" style="color:#1a1a2e;"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Cancel
                </button>
                <button type="button" id="confirmCompleteBtn" class="btn flex-fill"
                        style="background:#1b5e20; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-check mr-1"></i> Yes, Mark Completed
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#donationsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[5, 'desc']],
        language: {
            search: '',
            searchPlaceholder: 'Search donations...',
            info: 'Showing _START_ to _END_ of _TOTAL_ donations',
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

let pendingCompleteFormId = null;

function openCompleteModal(id, amount, donor) {
    pendingCompleteFormId = 'complete-form-' + id;
    document.getElementById('completeAmount').textContent = amount;
    document.getElementById('completeDonor').textContent = donor;
    $('#completeModal').modal('show');
}

document.getElementById('confirmCompleteBtn').addEventListener('click', function () {
    if (pendingCompleteFormId) {
        document.getElementById(pendingCompleteFormId).submit();
    }
});
</script>
@endpush
