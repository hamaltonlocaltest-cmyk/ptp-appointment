@extends('counselor.layouts.app')
@section('title', 'My Appointments')
@section('page-title', 'My Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">My Appointments</li>
@endsection

@section('content')
<div class="container-fluid py-2">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">My Appointments</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Manage your upcoming and past counselling sessions.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <style>
    .appt-card {
        border-radius:12px; border:1px solid #e0e4ec; margin-bottom:14px;
        overflow:hidden; transition:.2s; background:#fff;
    }
    .appt-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }
    .appt-left { width:6px; flex-shrink:0; }
    .appt-date-box { text-align:center; min-width:56px; padding:0 14px; border-right:1px solid #f0f1f5; }
    .appt-date-box .day { font-size:22px; font-weight:800; color:#1a1a2e; line-height:1.1; }
    .appt-date-box .month { font-size:11px; color:#9e9e9e; font-weight:600; text-transform:uppercase; }
    .status-badge { padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-pending   { background:#fff3cd; color:#856404; }
    .status-confirmed { background:#d4edda; color:#155724; }
    .status-cancelled { background:#f8d7da; color:#721c24; }
    .status-completed { background:#d1ecf1; color:#0c5460; }
    .reschedule-tag { background:#ede7f6; color:#4527a0; padding:2px 9px; border-radius:12px; font-size:10px; font-weight:700; }
    .action-btn { border-radius:20px; font-size:12px; padding:5px 14px; }
    </style>

   
    <h6 style="font-weight:700; color:#1b5e20; margin-bottom:14px;">
        <i class="fas fa-clock mr-2"></i> Upcoming ({{ $upcoming->count() }})
    </h6>

    @forelse($upcoming as $appt)
    @php
        $statusColor = ['pending'=>'#FFC107','confirmed'=>'#009643','cancelled'=>'#dc3545','completed'=>'#17a2b8'][$appt->status] ?? '#ccc';
        $completable = $appt->is_completable;
    @endphp
    <div class="appt-card d-flex">
        <div class="appt-left" style="background:{{ $statusColor }};"></div>
        <div class="appt-date-box d-flex flex-column justify-content-center py-3">
            <div class="day">{{ $appt->appointment_date->format('d') }}</div>
            <div class="month">{{ $appt->appointment_date->format('M Y') }}</div>
        </div>
        <div class="d-flex align-items-center flex-wrap p-3" style="flex:1; gap:4px;">
            <div style="flex:1; min-width:220px;">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span style="font-weight:700; font-size:15px; color:#1a1a2e;">{{ $appt->counselType->name }}</span>
                    <span class="status-badge status-{{ $appt->status }}">{{ ucfirst($appt->status) }}</span>
                    @if($appt->reschedules->count())
                    <span class="reschedule-tag"><i class="fas fa-history mr-1"></i>Rescheduled</span>
                    @endif
                </div>
                <div style="font-size:13px; color:#555;">
                    <i class="fas fa-user mr-1" style="color:#1b5e20;"></i>
                    {{ $appt->counselee->full_name }}
                    &nbsp;·&nbsp;
                    <i class="fas fa-clock mr-1" style="color:#1b5e20;"></i>
                    {{ $appt->formatted_time }}
                    &nbsp;·&nbsp;
                    <i class="fas fa-{{ $appt->mode === 'Online' ? 'video' : 'map-marker-alt' }} mr-1"></i>
                    {{ $appt->mode }}
                </div>
                @if($appt->notes)
                <div class="mt-1" style="font-size:12px; color:#9e9e9e; font-style:italic;">
                    "{{ Str::limit($appt->notes, 80) }}"
                </div>
                @endif
            </div>
            <div class="d-flex flex-wrap" style="gap:6px;">
                <a href="{{ route('counselor.appointments.show', $appt) }}" class="btn btn-sm btn-outline-secondary action-btn">
                    <i class="fas fa-eye mr-1"></i> View
                </a>
                @if($completable)
                <form action="{{ route('counselor.appointments.complete', $appt) }}" method="POST" id="complete-form-{{ $appt->id }}">
                    @csrf
                    <button type="button" class="btn btn-sm btn-outline-info action-btn"
                            onclick="openCompleteModal('{{ $appt->id }}', '{{ $appt->counselType->name }}', '{{ $appt->counselee->full_name }}')">
                        <i class="fas fa-check-double mr-1"></i> Mark Completed
                    </button>
                </form>
                @endif
                @if(in_array($appt->status, ['pending','confirmed']))
                <a href="{{ route('counselor.appointments.reschedule.edit', $appt) }}" class="btn btn-sm btn-outline-primary action-btn">
                    <i class="fas fa-calendar-alt mr-1"></i> Reschedule
                </a>
                <form action="{{ route('counselor.appointments.cancel', $appt) }}" method="POST" id="cancel-form-{{ $appt->id }}">
                    @csrf
                    <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                            onclick="openCancelModal('{{ $appt->id }}', '{{ $appt->counselType->name }}', '{{ $appt->appointment_date->format('l, M j, Y') }}', '{{ $appt->formatted_time }}')">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="background:#f8f9fc; border-radius:12px; border:1px dashed #ddd;">
        <i class="fas fa-calendar-alt fa-3x mb-3" style="color:#ddd;"></i>
        <p class="text-muted mb-0">No upcoming appointments.</p>
    </div>
    @endforelse

    
    @if($past->count())
    <h6 style="font-weight:700; color:#555; margin-top:28px; margin-bottom:14px;">
        <i class="fas fa-history mr-2"></i> Past Appointments
    </h6>

    @foreach($past as $appt)
    @php $statusColor = ['pending'=>'#FFC107','confirmed'=>'#009643','cancelled'=>'#dc3545','completed'=>'#17a2b8'][$appt->status] ?? '#ccc'; @endphp
    <div class="appt-card d-flex" style="opacity:.8;">
        <div class="appt-left" style="background:{{ $statusColor }};"></div>
        <div class="appt-date-box d-flex flex-column justify-content-center py-3">
            <div class="day">{{ $appt->appointment_date->format('d') }}</div>
            <div class="month">{{ $appt->appointment_date->format('M Y') }}</div>
        </div>
        <div class="d-flex align-items-center flex-wrap p-3" style="flex:1; gap:6px;">
            <div style="flex:1; min-width:200px;">
                <div class="d-flex align-items-center flex-wrap mb-1" style="gap:6px;">
                    <span style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $appt->counselType->name }}</span>
                    <span class="status-badge status-{{ $appt->status }}">{{ ucfirst($appt->status) }}</span>
                </div>
                <div style="font-size:12px; color:#777;">
                    <i class="fas fa-user mr-1"></i> {{ $appt->counselee->full_name }}
                    &nbsp;·&nbsp;
                    <i class="fas fa-clock mr-1"></i> {{ $appt->formatted_time }}
                </div>
            </div>
            <a href="{{ route('counselor.appointments.show', $appt) }}" class="btn btn-sm btn-outline-secondary action-btn">
                <i class="fas fa-eye mr-1"></i> View
            </a>
        </div>
    </div>
    @endforeach
    @endif

</div>


<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-calendar-times" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Cancel this appointment?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:16px;">
                    The counsellee will be notified by email. This action cannot be undone.
                </p>
                <div class="text-left" style="background:#f8f9fc; border-radius:10px; padding:14px 16px; font-size:13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Type</span>
                        <strong id="cancelType" style="color:#1a1a2e;"></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Date</span>
                        <strong id="cancelDate" style="color:#1a1a2e;"></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Time</span>
                        <strong id="cancelTime" style="color:#1a1a2e;"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Keep Appointment
                </button>
                <button type="button" id="confirmCancelBtn" class="btn flex-fill"
                        style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-times mr-1"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#e3f2fd; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-check-double" style="font-size:24px; color:#1565c0;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Mark session as completed?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:16px;">
                    The counsellee will receive a completion email.
                </p>
                <div class="text-left" style="background:#f8f9fc; border-radius:10px; padding:14px 16px; font-size:13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Type</span>
                        <strong id="completeType" style="color:#1a1a2e;"></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Counsellee</span>
                        <strong id="completeCounselee" style="color:#1a1a2e;"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Not Yet
                </button>
                <button type="button" id="confirmCompleteBtn" class="btn flex-fill"
                        style="background:#1565c0; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-check mr-1"></i> Yes, Completed
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let pendingCancelFormId = null;
function openCancelModal(id, type, date, time) {
    pendingCancelFormId = 'cancel-form-' + id;
    document.getElementById('cancelType').textContent = type;
    document.getElementById('cancelDate').textContent = date;
    document.getElementById('cancelTime').textContent = time;
    $('#cancelModal').modal('show');
}
document.getElementById('confirmCancelBtn').addEventListener('click', function () {
    if (pendingCancelFormId) document.getElementById(pendingCancelFormId).submit();
});

let pendingCompleteFormId = null;
function openCompleteModal(id, type, counselee) {
    pendingCompleteFormId = 'complete-form-' + id;
    document.getElementById('completeType').textContent = type;
    document.getElementById('completeCounselee').textContent = counselee;
    $('#completeModal').modal('show');
}
document.getElementById('confirmCompleteBtn').addEventListener('click', function () {
    if (pendingCompleteFormId) document.getElementById(pendingCompleteFormId).submit();
});
</script>
@endpush
