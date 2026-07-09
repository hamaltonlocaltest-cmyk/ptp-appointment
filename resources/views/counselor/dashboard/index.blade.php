@extends('counselor.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')


<div class="card mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center flex-wrap">
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
                    <span class="badge-active" style="background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">Active</span>
                @else
                    <span class="badge-pending" style="background:#fff3e0; color:#e65100; border:1px solid #ffcc80; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">{{ ucfirst($counselor->status) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-lg-4 col-6 mb-3">
        <a href="{{ route('counselor.appointments.index') }}" class="text-decoration-none">
        <div class="stat-card bg-admin">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $stats['total'] }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Total Appointments</p>
            <i class="fas fa-calendar-check" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <a href="{{ route('counselor.appointments.index') }}" class="text-decoration-none">
        <div class="stat-card bg-pending">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $stats['confirmed'] }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Confirmed</p>
            <i class="fas fa-check-circle" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <a href="{{ route('counselor.appointments.index') }}" class="text-decoration-none">
        <div class="stat-card bg-counselee">
            <h3 style="font-size:34px; font-weight:700; margin:0 0 4px;">{{ $stats['completed'] }}</h3>
            <p style="font-size:13px; margin:0; opacity:0.85;">Completed Sessions</p>
            <i class="fas fa-check-double" style="position:absolute; right:16px; top:50%; transform:translateY(-50%); font-size:52px; opacity:0.12;"></i>
        </div>
        </a>
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
            <span class="badge-active" style="background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600;">{{ ucfirst($appt->status) }}</span>
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

@endsection
