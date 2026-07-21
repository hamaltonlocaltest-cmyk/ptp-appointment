@extends('counselor.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')


<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center">
            <div class="avatar-circle mr-3" style="background:#0f5b5c; width:56px; height:56px; font-size:22px;">
                {{ strtoupper(substr($counselor->first_name, 0, 1)) }}
            </div>
            <div>
                <h4 style="margin:0; font-weight:700;">Welcome, {{ $counselor->first_name }}!</h4>
                <p style="margin:4px 0 0; opacity:0.8; font-size:13px;">
                    <i class="fas fa-briefcase-medical mr-1"></i> {{ $counselor->specialization }}
                    &nbsp;&nbsp;
                    <i class="fas fa-envelope mr-1"></i> {{ $counselor->email }}
                </p>
            </div>
            <div class="ml-auto">
                @if($counselor->status === 'active')
                    <span class="badge-active" style="background:#eaf7f5; color:#087a7f; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">Active</span>
                @else
                    <span class="badge-pending" style="background:#fff3e0; color:#e65100; border:1px solid #ffcc80; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">{{ ucfirst($counselor->status) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:15px;">
                <span style="font-weight:600;">
                    <i class="fas fa-bolt mr-1"></i> Quick Actions
                </span>
                <div class="d-flex flex-wrap" style="gap:10px;">
                    <a href="{{ route('counselor.leaves.create') }}" class="btn btn-sm"
                       style="background:#eaf7f5; color:#087a7f; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #087a7f;">
                        <i class="fas fa-calendar-plus mr-1"></i> Request Leave
                    </a>
                    <a href="{{ route('counselor.appointments.index') }}" class="btn btn-sm"
                       style="background:#eaf7f5; color:#087a7f; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #087a7f;">
                        <i class="fas fa-calendar-alt mr-1"></i> View All Appointments
                    </a>
                    <a href="{{ route('counselor.feedback.index') }}" class="btn btn-sm"
                       style="background:#fff3e0; color:#e65100; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #ffcc80;">
                        <i class="fas fa-star mr-1"></i> View Feedback
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <a href="{{ route('counselor.appointments.index') }}" class="text-decoration-none">
        <div class="stat-card bg-admin">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $stats['total'] }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Total Appointments</p>
            <i class="fas fa-calendar-check" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <a href="{{ route('counselor.appointments.index') }}" class="text-decoration-none">
        <div class="stat-card bg-pending">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $stats['confirmed'] }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Confirmed</p>
            <i class="fas fa-check-circle" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <a href="{{ route('counselor.appointments.index') }}" class="text-decoration-none">
        <div class="stat-card bg-counselee">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $stats['completed'] }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Completed Sessions</p>
            <i class="fas fa-check-double" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <a href="{{ route('counselor.feedback.index') }}" class="text-decoration-none">
        <div class="stat-card bg-completed">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $avgRating ?: '—' }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Avg Rating ({{ $feedbackCount }} reviews)</p>
            <i class="fas fa-star" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
    </div>
</div>

@if($needsAction->isNotEmpty())
<div class="row mb-3">
    <div class="col-12">
        <div class="card" style="border-left:4px solid #f8790e;">
            <div class="card-body p-3">
                <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#e65100; letter-spacing:.5px; margin-bottom:6px;">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Needs Action — {{ $needsAction->count() }} session(s) awaiting completion
                </div>
                @foreach($needsAction->take(3) as $na)
                <div class="d-flex align-items-center flex-wrap" style="gap:10px; padding:6px 0;">
                    <div style="flex:1; min-width:200px; font-size:13px;">
                        {{ $na->counselee->full_name ?? 'N/A' }} — {{ $na->counselType->name ?? '' }}
                        <span style="color:#9e9e9e;">({{ $na->appointment_date->format('M d, Y') }})</span>
                    </div>
                    <a href="{{ route('counselor.appointments.show', $na) }}" class="btn btn-sm"
                       style="background:#fff3e0; color:#e65100; border-radius:20px; padding:4px 14px; font-size:11px; font-weight:600; border:1px solid #ffcc80;">
                        Mark Completed
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<div class="card mb-4">
    <div class="card-header recent-header">
        <span class="recent-title">
            <i class="fas fa-calendar-day mr-2"></i> Today's Schedule
        </span>
    </div>
    <div class="card-body p-0">
        @forelse($todaysAppointments as $appt)
        <div class="d-flex align-items-center flex-wrap px-4 py-3" style="border-bottom:1px solid #f0f2f5;">
            <div style="flex:1; min-width:200px;">
                <div style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $appt->counselType->name }}</div>
                <div style="font-size:12px; color:#777;">
                    <i class="fas fa-user mr-1"></i>{{ $appt->counselee->full_name }}
                    &nbsp;·&nbsp;
                    <i class="fas fa-clock mr-1"></i>{{ $appt->formatted_time }}
                </div>
            </div>
            <span class="badge-active" style="background:#eaf7f5; color:#087a7f; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">{{ ucfirst($appt->status) }}</span>
        </div>
        @empty
        <div class="text-center py-4">
            <p class="text-muted mb-0">No appointments scheduled for today.</p>
        </div>
        @endforelse
    </div>
</div>

<div class="card">
    <div class="card-header recent-header">
        <span class="recent-title">
            <i class="fas fa-calendar-alt mr-2"></i> Upcoming Appointments
        </span>
        <a class="view-all-link" href="{{ route('counselor.appointments.index') }}">View All <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
	
	
	
    <div class="card-body p-0">
        @forelse($upcoming as $appt)
        <div class="d-flex align-items-center flex-wrap px-4 py-3" style="border-bottom:1px solid #f0f2f5;">
            <div style="min-width:60px; text-align:center; margin-right:16px;">
                <div style="font-size:20px; font-weight:800; color:#1a1a2e; line-height:1;">{{ $appt->appointment_date->format('d') }}</div>
                <div style="font-size:10px; color:#9e9e9e; font-weight:600; text-transform:uppercase;">{{ $appt->appointment_date->format('M') }}</div>
            </div>
            <div style="flex:1; min-width:200px;">
                <div style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $appt->counselType->name }}</div>
                <div style="font-size:12px; color:#777;">
                    <i class="fas fa-user mr-1"></i>{{ $appt->counselee->full_name }}
                    &nbsp;·&nbsp;
                    <i class="fas fa-clock mr-1"></i>{{ $appt->formatted_time }}
                </div>
            </div>
            <span class="badge-active" style="background:#eaf7f5; color:#087a7f; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">{{ ucfirst($appt->status) }}</span>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-calendar-alt" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
            <p class="text-muted mb-0">No upcoming appointments yet.</p>
            <small class="text-muted">Appointments booked by counselees will appear here.</small>
        </div>
        @endforelse
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header recent-header">
                <span class="recent-title">
                    <i class="fas fa-comment-dots mr-2"></i> Recent Feedback
                </span>
                <a class="view-all-link" href="{{ route('counselor.feedback.index') }}">View All <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="card-body p-0">
                @forelse($recentFeedback as $fb)
                <div class="px-4 py-3" style="border-bottom:1px solid #f0f2f5;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div style="font-weight:600; font-size:13px;">{{ $fb->counselee->full_name ?? 'N/A' }}</div>
                        <span class="badge-active" style="background:#eaf7f5; color:#087a7f; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">
                            <i class="fas fa-star mr-1"></i>{{ $fb->rating }}/5
                        </span>
                    </div>
                    @if($fb->comments)
                    <div style="font-size:12px; color:#777; margin-top:4px;">{{ \Illuminate\Support\Str::limit($fb->comments, 90) }}</div>
                    @endif
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No feedback received yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header recent-header">
                <span class="recent-title">
                    <i class="fas fa-plane-departure mr-2"></i> Leave Status
                </span>
                <a class="view-all-link" href="{{ route('counselor.leaves.index') }}">View All <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="card-body">
                @if($nextLeave)
                <div style="font-size:13px;">
                    <div style="font-weight:600; margin-bottom:4px;"><i class="fas fa-calendar-times mr-1"></i> Upcoming Leave</div>
                    <div style="color:#777;">{{ $nextLeave->start_date->format('M d, Y') }} — {{ $nextLeave->end_date->format('M d, Y') }}</div>
                    @if($nextLeave->reason)
                    <div style="color:#9e9e9e; font-size:12px; margin-top:4px;">{{ $nextLeave->reason }}</div>
                    @endif
                </div>
                @else
                <p class="text-muted mb-0" style="font-size:13px;">No upcoming leave scheduled.</p>
                @endif
                <a href="{{ route('counselor.leaves.create') }}" class="btn btn-sm mt-3"
                   style="background:#eaf7f5; color:#087a7f; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #087a7f;">
                    <i class="fas fa-calendar-plus mr-1"></i> Request Leave
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
