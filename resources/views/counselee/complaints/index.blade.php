@extends('counselee.layouts.app')
@section('title', 'My Complaints')
@section('page-title', 'My Complaints')
@section('breadcrumb')
    <li class="breadcrumb-item active">Complaints</li>
@endsection

@section('content')
<div class="container py-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">My Complaints</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Track complaints you've filed and their resolution status.</p>
        </div>
        <a href="{{ route('counselee.complaints.create') }}"
           class="btn" style="background:#D30404; color:#fff; border-radius:8px; padding:9px 20px; font-weight:600; font-size:13px;">
            <i class="fas fa-plus mr-1"></i> File a Complaint
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success d-flex align-items-center">
        <i class="fas fa-check-circle mr-3" style="font-size:20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <style>
    .complaint-card { border-radius:12px; border:1px solid #e0e4ec; margin-bottom:14px; overflow:hidden; transition:.2s; background:#fff; }
    .complaint-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }
    .status-badge { padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700; }
    .status-open      { background:#fff3cd; color:#856404; }
    .status-in_review { background:#cfe2ff; color:#084298; }
    .status-resolved  { background:#d4edda; color:#155724; }
    .status-closed    { background:#e2e3e5; color:#41464b; }
    </style>

    @forelse($complaints as $c)
    <div class="complaint-card p-3">
        <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap:8px;">
            <div>
                <div class="d-flex align-items-center flex-wrap mb-1" style="gap:8px;">
                    <span style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $c->subject }}</span>
                    <span class="status-badge status-{{ $c->status }}">{{ ucfirst(str_replace('_',' ',$c->status)) }}</span>
                </div>
                <div style="font-size:12px; color:#9e9e9e;">
                    Ref: <strong style="color:#666;">{{ $c->reference_number }}</strong>
                    &nbsp;·&nbsp; Filed {{ $c->created_at->format('M j, Y') }}
                </div>
                <div class="mt-2" style="font-size:13px; color:#555;">{{ $c->description }}</div>

                @if($c->resolution_notes)
                <div class="mt-2 p-2" style="background:#f8f9fc; border-left:3px solid #009643; border-radius:6px; font-size:12.5px; color:#444;">
                    <strong style="color:#009643;">Resolution:</strong> {{ $c->resolution_notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5" style="background:#f8f9fc; border-radius:12px; border:1px dashed #ddd;">
        <i class="fas fa-comment-slash fa-3x mb-3" style="color:#ddd;"></i>
        <p class="text-muted mb-2">You haven't filed any complaints.</p>
        <a href="{{ route('counselee.complaints.create') }}" class="btn btn-sm"
           style="background:#D30404; color:#fff; border-radius:20px; padding:7px 20px;">
            File a Complaint
        </a>
    </div>
    @endforelse

</div>
@endsection
