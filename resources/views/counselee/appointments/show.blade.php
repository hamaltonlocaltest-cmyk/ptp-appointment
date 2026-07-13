@extends('counselee.layouts.app')
@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('counselee.appointments.index') }}">My Appointments</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
<div class="py-4">
<div class="row justify-content-center">
<div class="col-lg-12">

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center">
        <i class="fas fa-check-circle mr-3" style="font-size:20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    @php
    $statusColor = ['pending'=>'#FFC107','confirmed'=>'#009643','cancelled'=>'#dc3545','completed'=>'#17a2b8'][$appointment->status] ?? '#ccc';
    @endphp

   
    <div class="card mb-3" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
                <div class="d-flex align-items-center" style="gap:14px;">
                    <div style="width:52px; height:52px; border-radius:50%; background:#0f5b5c; display:flex; align-items:center;
                         justify-content:center; color:#fff; font-size:20px; font-weight:700; flex-shrink:0;">
                        {{ $appointment->counselor ? strtoupper(substr($appointment->counselor->first_name, 0, 1)) : '?' }}
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:17px; color:#1a1a2e;">{{ $appointment->counselor->full_name ?? 'Unassigned Counselor' }}</div>
                        <div style="font-size:13px; color:#777;">{{ $appointment->counselType->name ?? 'Unknown Type' }}</div>
                    </div>
                </div>
                <span style="background:{{ $statusColor }}22; color:{{ $statusColor }}; padding:5px 16px; font-size:13px; font-weight:700; border-radius:20px;">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

           
            <div class="card mb-3" style="border-radius:14px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-header" style="background:#fff; border-radius:14px 14px 0 0;">
                    <span style="font-weight:600;"><i class="fas fa-info-circle mr-2" style="color:#0f5b5c;"></i> Session Details</span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div style="font-size:11px; color:#9e9e9e; text-transform:uppercase;">Date</div>
                            <div style="font-weight:600;">{{ $appointment->appointment_date->format('l, M j, Y') }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div style="font-size:11px; color:#9e9e9e; text-transform:uppercase;">Time</div>
                            <div style="font-weight:600;">{{ $appointment->formatted_time }}</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div style="font-size:11px; color:#9e9e9e; text-transform:uppercase;">Mode</div>
                            <div style="font-weight:600;">
                                <i class="fas fa-{{ $appointment->mode === 'Online' ? 'video' : 'map-marker-alt' }} mr-1"></i>
                                {{ $appointment->mode }}
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div style="font-size:11px; color:#9e9e9e; text-transform:uppercase;">Booked On</div>
                            <div style="font-weight:600;">{{ $appointment->created_at->format('M j, Y') }}</div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <div style="font-size:11px; color:#9e9e9e; text-transform:uppercase; margin-bottom:6px;">Your Notes</div>
                        <div class="p-3" style="background:#f8f9fc; border-radius:8px; font-size:13.5px; color:#444;">
                            {{ $appointment->notes ?: 'No notes provided.' }}
                        </div>
                    </div>
                </div>
            </div>

           
            @if($appointment->reschedules->isNotEmpty())
            <div class="card mb-3" style="border-radius:14px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-header" style="background:#fff; border-radius:14px 14px 0 0;">
                    <span style="font-weight:600;"><i class="fas fa-history mr-2" style="color:#4527a0;"></i> Reschedule History</span>
                </div>
                <div class="card-body p-4">
                    @foreach($appointment->reschedules as $r)
                    <div class="mb-3 pb-3" style="border-bottom:1px dashed #eee;">
                        <div style="font-size:12px; color:#9e9e9e;">
                            Rescheduled by <strong style="text-transform:capitalize;">{{ $r->rescheduled_by }}</strong> on {{ $r->created_at->format('M j, Y') }}
                        </div>
                        <div style="font-size:13px; margin-top:4px;">
                            <span style="text-decoration:line-through; color:#c62828;">{{ \Carbon\Carbon::parse($r->old_appointment_date)->format('M j') }}, {{ \Carbon\Carbon::parse($r->old_start_time)->format('g:i A') }}</span>
                            <i class="fas fa-arrow-right mx-2" style="color:#9e9e9e;"></i>
                            <span style="color:#1b5e20; font-weight:600;">{{ \Carbon\Carbon::parse($r->new_appointment_date)->format('M j') }}, {{ \Carbon\Carbon::parse($r->new_start_time)->format('g:i A') }}</span>
                        </div>
                        @if($r->reason)
                        <div style="font-size:12.5px; color:#666; margin-top:4px; font-style:italic;">"{{ $r->reason }}"</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            
            @if($appointment->complaints->isNotEmpty())
            <div class="card mb-3" style="border-radius:14px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-header" style="background:#fff; border-radius:14px 14px 0 0;">
                    <span style="font-weight:600;"><i class="fas fa-exclamation-circle mr-2" style="color:#c62828;"></i> Complaints About This Session</span>
                </div>
                <div class="card-body p-4">
                    @foreach($appointment->complaints as $c)
                    <div class="mb-2 pb-2" style="border-bottom:1px dashed #eee;">
                        <div style="font-weight:600; font-size:13px;">{{ $c->subject }}</div>
                        <div style="font-size:11px; color:#9e9e9e;">{{ $c->reference_number }} &middot; {{ ucfirst(str_replace('_',' ',$c->status)) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <div class="col-md-5">

         
            <div class="card mb-3" style="border-radius:14px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-header" style="background:#fff; border-radius:14px 14px 0 0;">
                    <span style="font-weight:600;"><i class="fas fa-user-tie mr-2" style="color:#0f5b5c;"></i> Your Counselor</span>
                </div>
                <div class="card-body p-4">
                    @if($appointment->counselor)
                    <div style="font-weight:700; color:#0f5b5c; font-size:15px;">
                        {{ $appointment->counselor->full_name }}
                        @if($appointment->counselor->status === 'deleted')<span class="badge-inactive ml-1" style="font-size:9px;">Deleted</span>@endif
                    </div>
                    @if($appointment->counselor->specialization)
                    <div style="font-size:12px; color:#7b1fa2; margin-top:2px;">{{ $appointment->counselor->specialization }}</div>
                    @endif
                    <div style="font-size:12px; color:#9e9e9e; margin-top:4px;">
                        @if($appointment->counselor->experience_years){{ $appointment->counselor->experience_years }} yrs experience @endif
                        @if($appointment->counselor->languages) &middot; {{ $appointment->counselor->languages }} @endif
                    </div>
                    @else
                    <p class="text-muted mb-0" style="font-size:13px;">This counselor's record no longer exists.</p>
                    @endif
                </div>
            </div>

           
            <div class="card mb-3" style="border-radius:14px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-header" style="background:#fff; border-radius:14px 14px 0 0;">
                    <span style="font-weight:600;"><i class="fas fa-star mr-2" style="color:#f9a825;"></i> Feedback</span>
                </div>
                <div class="card-body p-4">
                    @if($appointment->feedback)
                        <div style="color:#f9a825; font-size:18px;">
                            @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $appointment->feedback->rating ? 'color:#e0e4ec;' : '' }}"></i>@endfor
                        </div>
                        @if($appointment->feedback->comments)
                        <div class="mt-2" style="font-size:13px; color:#555; font-style:italic;">"{{ $appointment->feedback->comments }}"</div>
                        @endif
                    @elseif($appointment->status === 'completed')
                        <p style="font-size:13px; color:#666; margin-bottom:12px;">You haven't rated this session yet.</p>
                        <a href="{{ route('counselee.appointments.feedback.create', $appointment) }}"
                           class="btn btn-block" style="background:#0f5b5c; color:#fff; border-radius:20px; font-size:13px; font-weight:600; padding:8px;">
                            <i class="fas fa-star mr-1"></i> Leave Feedback
                        </a>
                    @else
                        <p style="font-size:12.5px; color:#9e9e9e; margin-bottom:0;">Feedback becomes available once this session is marked completed.</p>
                    @endif
                </div>
            </div>

           
            <div class="card" style="border-radius:14px; border:none; box-shadow:0 2px 12px rgba(0,0,0,.06);">
                <div class="card-body p-3">
                    <a href="{{ route('counselee.appointments.index') }}" class="btn btn-block btn-light mb-2" style="border:1px solid #e0e4ec;">
                        <i class="fas fa-arrow-left mr-1"></i> Back to List
                    </a>
                    @if(in_array($appointment->status, ['pending','confirmed']))
                    <a href="{{ route('counselee.appointments.reschedule.edit', $appointment) }}" class="btn btn-block btn-outline-primary mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Reschedule
                    </a>
                    <form action="{{ route('counselee.appointments.cancel', $appointment) }}" method="POST" id="cancelForm">
                        @csrf
                        <button type="button" class="btn btn-block btn-outline-danger" data-toggle="modal" data-target="#cancelModal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
</div>
</div>

@if(in_array($appointment->status, ['pending','confirmed']))
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-calendar-times" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Cancel this appointment?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    This will free up the slot for someone else. This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Keep Appointment
                </button>
                <button type="button" class="btn flex-fill" onclick="document.getElementById('cancelForm').submit();"
                        style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-times mr-1"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
