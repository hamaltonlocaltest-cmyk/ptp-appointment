@extends('counselee.layouts.app')
@section('title', 'My Feedback')
@section('page-title', 'My Feedback')
@section('breadcrumb')
    <li class="breadcrumb-item active">Feedback</li>
@endsection

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">My Feedback</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Feedback you've submitted for your completed sessions.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center">
        <i class="fas fa-check-circle mr-3" style="font-size:20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <style>
    .feedback-card { border-radius:12px; border:1px solid #e0e4ec; margin-bottom:14px; overflow:hidden; transition:.2s; background:#fff; }
    .feedback-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }
    .stars { color:#f9a825; font-size:15px; letter-spacing:1px; }
    .stars .empty { color:#e0e4ec; }
    </style>

    @forelse($feedbacks as $f)
    <div class="feedback-card p-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap:8px;">
            <div style="flex:1; min-width:220px;">
                <div class="d-flex align-items-center flex-wrap mb-1" style="gap:8px;">
                    <span style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $f->appointment->counselType->name }}</span>
                    <span class="stars">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i > $f->rating ? 'empty' : '' }}"></i>@endfor
                    </span>
                </div>
                <div style="font-size:12px; color:#9e9e9e;">
                    <i class="fas fa-user-tie mr-1"></i> {{ $f->counselor->full_name }}
                    &nbsp;·&nbsp;
                    {{ $f->appointment->appointment_date->format('M j, Y') }}
                </div>
                @if($f->comments)
                <div class="mt-2" style="font-size:13px; color:#555; font-style:italic;">"{{ $f->comments }}"</div>
                @endif
            </div>
            <div style="font-size:11px; color:#bbb; white-space:nowrap;">{{ $f->submitted_at?->format('M j, Y') }}</div>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="background:#f8f9fc; border-radius:12px; border:1px dashed #ddd;">
        <i class="fas fa-comment-dots fa-3x mb-3" style="color:#ddd;"></i>
        <p class="text-muted mb-2">You haven't left any feedback yet.</p>
        <a href="{{ route('counselee.appointments.index') }}" class="btn btn-sm"
           style="background:#0f5b5c; color:#fff; border-radius:20px; padding:7px 20px;">
            View My Appointments
        </a>
    </div>
    @endforelse

</div>
@endsection
