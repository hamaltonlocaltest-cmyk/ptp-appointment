@extends('admin.layouts.app')
@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.appointments.index') }}">Appointments</a></li>
    <li class="breadcrumb-item active">#{{ $appointment->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap:10px;">
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-light" style="border-radius:7px; border:1px solid #e0e4ec;">
        <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
    @if(in_array($appointment->status, ['pending','confirmed']))
    <div class="d-flex" style="gap:8px;">
        <a href="{{ route('admin.appointments.reschedule.edit', $appointment) }}" class="btn btn-sm" style="background:#ede7f6; color:#4527a0; border-radius:7px; padding:7px 18px;">
            <i class="fas fa-calendar-alt mr-1"></i> Reschedule
        </a>
        <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" id="cancel-form">
            @csrf
            <button type="button" class="btn btn-sm" style="background:#ffebee; color:#c62828; border-radius:7px; padding:7px 18px;"
                    onclick="$('#cancelModal').modal('show')">
                <i class="fas fa-times mr-1"></i> Cancel
            </button>
        </form>
    </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                <span style="color:#1f8582; font-weight:600; font-size:14px; flex:auto;">
                    <i class="fas fa-calendar-check mr-2"></i>{{ $appointment->counselType->name ?? 'Unknown Type' }}
                </span>
                @php $badge = ['pending'=>'badge-pending','confirmed'=>'badge-active','cancelled'=>'badge-inactive','completed'=>'badge-active'][$appointment->status] ?? 'badge-pending'; @endphp
                <span class="{{ $badge }}"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>{{ ucfirst($appointment->status) }}</span>
            </div>
            <div class="card-body">
                <dl class="row mb-0" style="font-size:13px;">
                    <dt class="col-sm-4 text-muted">Date</dt>
                    <dd class="col-sm-8">{{ $appointment->appointment_date->format('l, F j, Y') }}</dd>

                    <dt class="col-sm-4 text-muted">Time</dt>
                    <dd class="col-sm-8">{{ $appointment->formatted_time }}</dd>

                    <dt class="col-sm-4 text-muted">Mode</dt>
                    <dd class="col-sm-8"><i class="fas fa-{{ $appointment->mode === 'Online' ? 'video' : 'map-marker-alt' }} mr-1"></i>{{ $appointment->mode }}</dd>

                    @if($appointment->notes)
                    <dt class="col-sm-4 text-muted">Counsellee Notes</dt>
                    <dd class="col-sm-8">{{ $appointment->notes }}</dd>
                    @endif

                    @if($appointment->counselor_notes)
                    <dt class="col-sm-4 text-muted">Counselor Notes</dt>
                    <dd class="col-sm-8">{{ $appointment->counselor_notes }}</dd>
                    @endif

                    <dt class="col-sm-4 text-muted">Booked</dt>
                    <dd class="col-sm-8">{{ $appointment->created_at->format('M d, Y g:i A') }}</dd>

                    @if($appointment->cancelled_at)
                    <dt class="col-sm-4 text-muted">Cancelled</dt>
                    <dd class="col-sm-8">{{ $appointment->cancelled_at->format('M d, Y g:i A') }} (by {{ $appointment->cancelled_by }})</dd>
                    @endif

                    @if($appointment->completed_at)
                    <dt class="col-sm-4 text-muted">Completed</dt>
                    <dd class="col-sm-8">{{ $appointment->completed_at->format('M d, Y g:i A') }}</dd>
                    @endif
                </dl>
            </div>
        </div>

       
        @if($appointment->reschedules->count())
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="color:#1f8582; font-weight:600; font-size:14px;">
                    <i class="fas fa-history mr-2"></i>Reschedule History
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Changed</th>
                                <th>Previous</th>
                                <th>New</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($appointment->reschedules as $log)
                            <tr>
                                <td style="font-size:12px; color:#9e9e9e;">{{ $log->created_at->format('M d, Y g:i A') }}</td>
                                <td style="font-size:13px;">
                                    {{ $log->old_appointment_date->format('M d, Y') }}<br>
                                    <span style="color:#9e9e9e; font-size:12px;">{{ $log->formatted_old_time }} &middot; {{ optional($log->oldCounselor)->full_name ?? '—' }}</span>
                                </td>
                                <td style="font-size:13px;">
                                    {{ $log->new_appointment_date->format('M d, Y') }}<br>
                                    <span style="color:#9e9e9e; font-size:12px;">{{ $log->formatted_new_time }} &middot; {{ optional($log->newCounselor)->full_name ?? '—' }}</span>
                                </td>
                                <td style="font-size:13px; text-transform:capitalize;">{{ $log->rescheduled_by }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="color:#1f8582; font-weight:600; font-size:14px;">
                    <i class="fas fa-user mr-2"></i>Counsellee
                </span>
            </div>
            <div class="card-body">
                @if($appointment->counselee)
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-circle mr-3">
                        {{ strtoupper(substr($appointment->counselee->first_name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600; color:#1a1a2e;">
                            {{ $appointment->counselee->full_name }}
                            @if($appointment->counselee->status === 'deleted')
                            <span class="badge-inactive ml-1" style="font-size:10px;">Deleted</span>
                            @endif
                        </div>
                        <div style="font-size:12px; color:#9e9e9e;">{{ $appointment->counselee->email }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.counselees.show', $appointment->counselee) }}" style="font-size:12px; font-weight:600; color:#1f8582;">
                    View Profile <i class="fas fa-arrow-right ml-1"></i>
                </a>
                @else
                <p class="text-muted mb-0" style="font-size:13px;"><i class="fas fa-exclamation-triangle mr-1"></i> This counsellee record no longer exists.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="color:#1f8582; font-weight:600; font-size:14px;">
                    <i class="fas fa-user-md mr-2"></i>Counselor
                </span>
            </div>
            <div class="card-body">
                @if($appointment->counselor)
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-circle mr-3">
                        {{ strtoupper(substr($appointment->counselor->first_name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600; color:#1a1a2e;">
                            {{ $appointment->counselor->full_name }}
                            @if($appointment->counselor->status === 'deleted')
                            <span class="badge-inactive ml-1" style="font-size:10px;">Deleted</span>
                            @endif
                        </div>
                        <div style="font-size:12px; color:#9e9e9e;">{{ $appointment->counselor->email }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.counselors.show', $appointment->counselor) }}" style="font-size:12px; font-weight:600; color:#1f8582;">
                    View Profile <i class="fas fa-arrow-right ml-1"></i>
                </a>
                @else
                <p class="text-muted mb-0" style="font-size:13px;"><i class="fas fa-exclamation-triangle mr-1"></i> This counselor record no longer exists.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-calendar-times" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Cancel this appointment?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    Both the counsellee and counselor will be notified by email. This action cannot be undone.
                </p>
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

@endsection

@push('scripts')
<script>
document.getElementById('confirmCancelBtn').addEventListener('click', function () {
    document.getElementById('cancel-form').submit();
});
</script>
@endpush
