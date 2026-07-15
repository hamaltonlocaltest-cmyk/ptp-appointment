@extends('counselor.layouts.app')
@section('title', 'My Leaves')
@section('page-title', 'My Leaves')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Leaves</li>
@endsection

@section('content')
<div class="container-fluid py-2">

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4" style="border-bottom:1px solid #ddd; padding-bottom:10px;">
        <div>
            <h4 class="mb-1" style="color:#1a1a2e; font-weight:700; font-size:20px;">My Leaves</h4>
            <p class="text-muted mb-0" style="font-size:13px;">
                Dates you mark here will not be offered to counselees when booking — you'll simply show as unavailable.
            </p>
        </div>
        <a href="{{ route('counselor.leaves.create') }}" class="btn btn-primary" style="background:#1b5e20; border-color:#1b5e20;">
            <i class="fas fa-plus me-1"></i> Add Leave
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center">
        <i class="fas fa-check-circle mr-3" style="font-size:20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <style>
    .leave-card { border-radius:12px; border:1px solid #e0e4ec; margin-bottom:14px; overflow:hidden; background:#fff; }
    .leave-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }
    .leave-range { font-weight:700; font-size:14px; color:#1a1a2e; }
    .leave-tag { padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700; background:#e8f5e9; color:#1b5e20; }
    </style>

    <h6 style="font-weight:700; margin-bottom:14px;">
        <i class="fas fa-calendar-minus mr-2"></i> Upcoming ({{ $upcoming->count() }})
    </h6>

    @forelse($upcoming as $leave)
    <div class="leave-card p-3 d-flex align-items-center flex-wrap" style="gap:10px;">
        <div style="flex:1; min-width:220px;">
            <div class="leave-range">
                @if($leave->start_date->eq($leave->end_date))
                    {{ $leave->start_date->format('l, M j, Y') }}
                @else
                    {{ $leave->start_date->format('M j, Y') }} &nbsp;&rarr;&nbsp; {{ $leave->end_date->format('M j, Y') }}
                @endif
                <span class="leave-tag ml-2">{{ $leave->created_by === 'admin' ? 'Set by Admin' : 'Self' }}</span>
            </div>
            @if($leave->reason)
            <div style="font-size:12.5px; color:#777; margin-top:2px;">{{ $leave->reason }}</div>
            @endif
        </div>
        <form action="{{ route('counselor.leaves.destroy', $leave) }}" method="POST" id="delete-form-{{ $leave->id }}">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="openCancelModal('{{ $leave->id }}')">
                <i class="fas fa-trash mr-1"></i> Cancel
            </button>
        </form>
    </div>
    @empty
    <div class="text-center py-4 mb-4" style="background:#f8f9fc; border-radius:12px; border:1px dashed #ddd;">
        <p class="text-muted mb-0" style="font-size:13px;">No upcoming leave. You're available on all your scheduled days.</p>
    </div>
    @endforelse

    @if($past->count())
    <h6 style="font-weight:700; color:#555; margin-top:28px; margin-bottom:14px;">
        <i class="fas fa-history mr-2"></i> Past Leave
    </h6>
    @foreach($past as $leave)
    <div class="leave-card p-3" style="opacity:.75;">
        <div class="leave-range">
            @if($leave->start_date->eq($leave->end_date))
                {{ $leave->start_date->format('M j, Y') }}
            @else
                {{ $leave->start_date->format('M j, Y') }} &rarr; {{ $leave->end_date->format('M j, Y') }}
            @endif
        </div>
        @if($leave->reason)
        <div style="font-size:12.5px; color:#777;">{{ $leave->reason }}</div>
        @endif
    </div>
    @endforeach
    @endif

</div>

<div class="modal fade" id="cancelLeaveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-calendar-times" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Cancel this leave?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    You'll become bookable again on these dates.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Keep Leave
                </button>
                <button type="button" id="confirmCancelLeaveBtn" class="btn flex-fill"
                        style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-trash mr-1"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pendingDeleteFormId = null;

function openCancelModal(id) {
    pendingDeleteFormId = 'delete-form-' + id;
    $('#cancelLeaveModal').modal('show');
}

document.getElementById('confirmCancelLeaveBtn').addEventListener('click', function () {
    if (pendingDeleteFormId) {
        document.getElementById(pendingDeleteFormId).submit();
    }
});
</script>
@endpush
