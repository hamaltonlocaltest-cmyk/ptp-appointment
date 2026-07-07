@extends('admin.layouts.app')
@section('title', 'Feedback Details')
@section('page-title', 'Feedback Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.feedback.index') }}">Feedback</a></li>
    <li class="breadcrumb-item active">#{{ $feedback->id }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

    <div class="card mb-3">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <div style="font-size:12px; color:#9e9e9e;">Rating</div>
                    <div style="color:#f9a825; font-size:26px;">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > $feedback->rating ? 'color:#e0e4ec;' : '' }}"></i>@endfor
                    </div>
                </div>
                <div class="text-right">
                    <div style="font-size:12px; color:#9e9e9e;">Submitted</div>
                    <div style="font-weight:600;">{{ $feedback->submitted_at?->format('M j, Y g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header" style="background:#fff;">
            <span style="font-weight:600;"><i class="fas fa-info-circle mr-2"></i> Session Details</span>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Counselee</div>
                    <div style="font-weight:600;">{{ $feedback->counselee->full_name }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Counselor</div>
                    <div style="font-weight:600;">{{ $feedback->counselor->full_name }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Counselling Area</div>
                    <div style="font-weight:600;">{{ $feedback->appointment->counselType->name }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Session Date</div>
                    <div style="font-weight:600;">{{ $feedback->appointment->appointment_date->format('M j, Y') }}</div>
                </div>
            </div>

            @if($feedback->comments)
            <div class="mt-3">
                <div style="font-size:12px; color:#9e9e9e; margin-bottom:6px;">Comments</div>
                <div class="p-3" style="background:#f8f9fc; border-radius:8px; font-size:13.5px; color:#444; font-style:italic;">
                    "{{ $feedback->comments }}"
                </div>
            </div>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.feedback.index') }}" class="btn btn-light" style="border:1px solid #e0e4ec;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Feedback List
    </a>

</div>
</div>
@endsection
