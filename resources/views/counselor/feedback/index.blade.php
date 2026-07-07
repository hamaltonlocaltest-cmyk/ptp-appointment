@extends('counselor.layouts.app')
@section('title', 'Feedback')
@section('page-title', 'Feedback')
@section('breadcrumb')
    <li class="breadcrumb-item active">Feedback</li>
@endsection

@section('content')
<div class="container-fluid py-2">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">Feedback</h4>
            <p class="text-muted mb-0" style="font-size:13px;">What counselees are saying about your sessions.</p>
        </div>
        @if($feedbacks->isNotEmpty())
        <div class="d-flex align-items-center" style="gap:8px;">
            <span style="font-size:28px; color:#f9a825; font-weight:800;">{{ $averageRating }}</span>
            <div>
                <div style="color:#f9a825; font-size:15px; letter-spacing:2px;">
                    @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="{{ $i > round($averageRating) ? 'color:#e0e4ec;' : '' }}"></i>@endfor
                </div>
                <div style="font-size:11px; color:#9e9e9e;">{{ $feedbacks->count() }} review{{ $feedbacks->count() == 1 ? '' : 's' }}</div>
            </div>
        </div>
        @endif
    </div>

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
                    <span style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $f->counselee->full_name }}</span>
                    <span class="stars">
                        @for($i=1;$i<=5;$i++)<i class="fas fa-star {{ $i > $f->rating ? 'empty' : '' }}"></i>@endfor
                    </span>
                </div>
                <div style="font-size:12px; color:#9e9e9e;">
                    {{ $f->appointment->counselType->name }} &middot; {{ $f->appointment->appointment_date->format('M j, Y') }}
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
        <p class="text-muted mb-0">No feedback yet.</p>
    </div>
    @endforelse

</div>
@endsection
