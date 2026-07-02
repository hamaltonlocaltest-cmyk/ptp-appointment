@extends('counselee.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

    {{-- Welcome Banner --}}
    <div class="card" style="background: linear-gradient(135deg, #4a148c, #6a1b9a); color:#fff; border-radius:12px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center flex-wrap" style="gap:16px;">
                <div class="avatar-circle"
                     style="background:rgba(255,255,255,0.2); width:60px; height:60px; font-size:24px; flex-shrink:0;">
                    {{ strtoupper(substr($counselee->first_name, 0, 1)) }}
                </div>
                <div style="flex:1;">
                    <h4 style="margin:0; font-weight:700;">Welcome back, {{ $counselee->first_name }}!</h4>
                    <p style="margin:5px 0 0; opacity:0.8; font-size:13px;">
                        <i class="fas fa-envelope mr-1"></i> {{ $counselee->email }}
                        @if($counselee->telephone1)
                        &nbsp;&nbsp;<i class="fas fa-phone mr-1"></i> {{ $counselee->telephone1 }}
                        @endif
                    </p>
                    <p style="margin:3px 0 0; opacity:0.7; font-size:12px;">
                        @if($counselee->gender)
                        <i class="fas fa-venus-mars mr-1"></i> {{ ucfirst($counselee->gender) }} &nbsp;&nbsp;
                        @endif
                        @if($counselee->birthdate)
                        <i class="fas fa-birthday-cake mr-1"></i> {{ $counselee->birthdate->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                <div>
                    @if($counselee->status === 'active')
                        <span class="badge-active">Active</span>
                    @else
                        <span class="badge-inactive">{{ ucfirst($counselee->status) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row mb-3">
        <div class="col-lg-3 col-6 mb-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#4a148c,#6a1b9a);">
                <h3>{{ $counts['total'] }}</h3>
                <p>Total Appointments</p>
                <i class="fas fa-calendar-check stat-icon"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
                <h3>{{ $counts['pending'] }}</h3>
                <p>Pending</p>
                <i class="fas fa-clock stat-icon"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#1565c0,#1976d2);">
                <h3>{{ $counts['confirmed'] }}</h3>
                <p>Confirmed</p>
                <i class="fas fa-calendar-check stat-icon"></i>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
                <h3>{{ $counts['completed'] }}</h3>
                <p>Completed</p>
                <i class="fas fa-check-double stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Quick Actions --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <span style="color:#4a148c; font-size:14px; font-weight:600;">
                        <i class="fas fa-bolt mr-2"></i> Quick Actions
                    </span>
                </div>
                <div class="card-body">
                    <a href="{{ route('counselee.appointments.create') }}" class="quick-action-btn">
                        <div class="qa-icon" style="background:#f3e5f5; color:#4a148c;">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div>
                            <div class="qa-title">Book Appointment</div>
                            <div class="qa-sub">Schedule with a counselor</div>
                        </div>
                    </a>
                    <a href="{{ route('counselee.appointments.index') }}" class="quick-action-btn">
                        <div class="qa-icon" style="background:#e3f2fd; color:#1565c0;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="qa-title">My Appointments</div>
                            <div class="qa-sub">View all your appointments</div>
                        </div>
                    </a>
                    <a href="#" class="quick-action-btn">
                        <div class="qa-icon" style="background:#e8f5e9; color:#1b5e20;">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div>
                            <div class="qa-title">Update Profile</div>
                            <div class="qa-sub">Edit your personal info</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Counselling Areas --}}
            @if($counselee->counselTypes->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <span style="color:#4a148c; font-size:14px; font-weight:600;">
                        <i class="fas fa-hand-holding-heart mr-2"></i> My Counselling Areas
                    </span>
                </div>
                <div class="card-body">
                    @foreach($counselee->counselTypes as $type)
                    <span class="mb-2 d-inline-block" style="background:#f3e9ff; color:#4a148c; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:600; margin-right:4px; margin-bottom:6px;">
                        @if($type->icon)<i class="{{ $type->icon }} mr-1"></i>@endif {{ $type->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Recent Appointments --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span style="color:#4a148c; font-size:14px; font-weight:600;">
                        <i class="fas fa-calendar-check mr-2"></i> Recent Appointments
                    </span>
                    <a href="{{ route('counselee.appointments.index') }}"
                       style="font-size:12px; color:#4a148c; text-decoration:none;">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentAppointments->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times" style="font-size:40px; color:#e0e0e0; display:block; margin-bottom:10px;"></i>
                        <p class="text-muted mb-2">No appointments yet.</p>
                        <a href="{{ route('counselee.appointments.create') }}"
                           class="btn btn-sm" style="background:#4a148c; color:#fff; border-radius:20px; padding:7px 20px; font-size:13px;">
                            Book Your First Appointment
                        </a>
                    </div>
                    @else
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Counselor</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $appt)
                            <tr>
                                <td>
                                    @if($appt->counselType->icon)
                                    <i class="{{ $appt->counselType->icon }} mr-1" style="color:{{ $appt->counselType->color ?: '#4a148c' }};"></i>
                                    @endif
                                    {{ $appt->counselType->name }}
                                </td>
                                <td>
                                    <div style="font-weight:600;">{{ $appt->counselor->full_name }}</div>
                                    <div style="font-size:11px; color:#9e9e9e;">{{ $appt->mode }}</div>
                                </td>
                                <td>
                                    <div>{{ $appt->appointment_date->format('M d, Y') }}</div>
                                    <div style="font-size:11px; color:#9e9e9e;">{{ $appt->formatted_time }}</div>
                                </td>
                                <td>
                                    <span class="status-{{ $appt->status }}">{{ ucfirst($appt->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>

            {{-- Upcoming next appointment highlight --}}
            @if($nextAppointment)
            <div class="card" style="border-left:4px solid #D30404;">
                <div class="card-body p-3">
                    <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#D30404; letter-spacing:.5px; margin-bottom:6px;">
                        <i class="fas fa-bell mr-1"></i> Next Upcoming Appointment
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap:12px;">
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:15px;">{{ $nextAppointment->counselType->name }}</div>
                            <div style="font-size:13px; color:#555;">
                                with {{ $nextAppointment->counselor->full_name }}
                                &nbsp;·&nbsp;
                                {{ $nextAppointment->appointment_date->format('l, M j') }}
                                at {{ \Carbon\Carbon::createFromTimeString($nextAppointment->start_time)->format('g:i A') }}
                            </div>
                        </div>
                        <span class="status-{{ $nextAppointment->status }}">{{ ucfirst($nextAppointment->status) }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>

@endsection
