@extends('counselee.layouts.app')
@section('title', 'Confirm Appointment')
@section('page-title', 'Confirm Appointment')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('counselee.appointments.create') }}">Book</a></li>
    <li class="breadcrumb-item active">Confirm</li>
@endsection

@section('content')
<div class="container py-4">
<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="text-center mb-4">
        <div style="font-size:48px; color:#009643; margin-bottom:12px;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <h4 style="font-weight:700; color:#1a1a2e;">Review Your Appointment</h4>
        <p class="text-muted">Please review the details below before confirming your booking.</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">

     
            <div class="d-flex align-items-center mb-4 p-3"
                 style="background:#f3e9ff; border-radius:10px; border:1px solid #d4a8f0;">
                <div class="mr-3" style="width:48px; height:48px; border-radius:50%; background:#4a148c;
                     display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px; font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($counselor->first_name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight:700; color:#4a148c; font-size:15px;">{{ $counselor->full_name }}</div>
                    <div style="font-size:12px; color:#7b1fa2;">{{ $counselor->specialization }}</div>
                    <div style="font-size:12px; color:#9e9e9e;">{{ $counselor->experience_years }} yrs experience · {{ $counselor->training_level }}</div>
                </div>
                <div class="ml-auto text-right">
                    <span class="badge" style="background:#4a148c; color:#fff; padding:5px 12px; border-radius:20px; font-size:12px;">
                        {{ $counselor->mode }}
                    </span>
                </div>
            </div>

       
            <div class="row" style="font-size:14px;">
                <div class="col-6 mb-4">
                    <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                        Counselling Area
                    </div>
                    <div style="font-weight:700; color:#333;">
                        @if($counselType->icon)<i class="{{ $counselType->icon }} mr-1" style="color:{{ $counselType->color ?: '#D30404' }};"></i>@endif
                        {{ $counselType->name }}
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                        Date
                    </div>
                    <div style="font-weight:700; color:#333;">{{ $date->format('l, F j, Y') }}</div>
                </div>
                <div class="col-6 mb-4">
                    <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                        Time
                    </div>
                    <div style="font-weight:700; color:#333;">
                        {{ \Carbon\Carbon::createFromTimeString($validated['start_time'])->format('g:i A') }}
                        –
                        {{ \Carbon\Carbon::createFromTimeString($validated['end_time'])->format('g:i A') }}
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                        Duration
                    </div>
                    <div style="font-weight:700; color:#333;">60 minutes</div>
                </div>
                <div class="col-6 mb-2">
                    <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                        Languages
                    </div>
                    <div style="font-weight:600; color:#333;">{{ $counselor->languages ?: '—' }}</div>
                </div>
                <div class="col-6 mb-2">
                    <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px;">
                        Status After Booking
                    </div>
                    <div>
                        <span style="background:#d4edda; color:#155724; padding:3px 12px; border-radius:20px; font-size:12px; font-weight:600;">
                            <i class="fas fa-check-circle mr-1"></i> Confirmed
                        </span>
                    </div>
                </div>
            </div>

            @if(!empty($validated['notes']))
            <div class="mt-3 p-3" style="background:#f8f9fc; border-radius:8px; border-left:3px solid #D30404;">
                <div class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; margin-bottom:4px;">Your Notes</div>
                <div style="font-size:14px; color:#444;">{{ $validated['notes'] }}</div>
            </div>
            @endif

        </div>
    </div>

    <form action="{{ route('counselee.appointments.store') }}" method="POST" class="mt-4">
        @csrf
        <input type="hidden" name="counsel_type_id"  value="{{ $validated['counsel_type_id'] }}">
        <input type="hidden" name="appointment_date" value="{{ $validated['appointment_date'] }}">
        <input type="hidden" name="start_time"       value="{{ $validated['start_time'] }}">
        <input type="hidden" name="end_time"         value="{{ $validated['end_time'] }}">
        <input type="hidden" name="counselor_id"     value="{{ $validated['counselor_id'] }}">
        <input type="hidden" name="notes"            value="{{ $validated['notes'] ?? '' }}">

        <div class="d-flex justify-content-between">
            <a href="{{ route('counselee.appointments.create') }}"
               class="btn btn-light" style="border-radius:8px; padding:11px 24px; border:1px solid #ddd;">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <button type="submit" class="btn"
                    style="background:#D30404; color:#fff; border-radius:8px; padding:11px 32px; font-weight:700; font-size:15px;">
                <i class="fas fa-check-circle mr-2"></i> Confirm Appointment
            </button>
        </div>
    </form>

    <p class="text-center text-muted mt-3" style="font-size:12px;">
        <i class="fas fa-envelope mr-1"></i> A confirmation email will be sent to you after booking.
    </p>

</div>
</div>
</div>
@endsection
