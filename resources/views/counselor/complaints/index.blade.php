@extends('counselor.layouts.app')
@section('title', 'Complaints')
@section('page-title', 'Complaints')
@section('breadcrumb')
    <li class="breadcrumb-item active">Complaints</li>
@endsection

@section('content')

<style>
	.complaints-header {
    gap: 1rem;
	border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:20px;
}

.complaints-title {
    color: #1a1a2e;
    font-weight: 600;
    margin-bottom: .25rem;
	font-size: 20px;
}

.complaints-subtitle {
    color: #6c757d;
    font-size: 0.813rem;
}

.complaint-btn {
    background: #1f8582;
    color: #fff;
    border-radius: 8px;
    padding: 9px 20px;
    font-size: 0.813rem;
    font-weight: 600;
    white-space: nowrap;
    transition: .3s ease;
}

.complaint-btn:hover,
.complaint-btn:focus {
    background: #1f8582;
    color: #fff;
}

@media (max-width: 767.98px) {
    .complaints-header {
        align-items: stretch;
    }

    .complaint-btn {
        width: 100%;
        text-align: center;
    }
}
</style>

<div class="container-fluid py-2">

    <div class="complaints-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
		<div>
			<h4 class="complaints-title mb-1">Complaints</h4>
			<p class="complaints-subtitle mb-0">
				Complaints you've filed, and complaints filed about your sessions.
			</p>
		</div>

		<a href="{{ route('counselor.complaints.create') }}" class="btn btn-primary">
			<i class="fas fa-plus me-1"></i> File a Complaint
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

    <h6 style="font-weight:700; margin-bottom:14px;">
        <i class="fas fa-file-signature mr-2"></i> Filed by You ({{ $filed->count() }})
    </h6>

    @forelse($filed as $c)
    <div class="complaint-card p-3">
        <div class="d-flex align-items-center flex-wrap mb-1" style="gap:8px;">
            <span style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $c->subject }}</span>
            <span class="status-badge status-{{ $c->status }}">{{ ucfirst(str_replace('_',' ',$c->status)) }}</span>
        </div>
        <div style="font-size:12px; color:#9e9e9e;">
            Ref: <strong style="color:#666;">{{ $c->reference_number }}</strong> &nbsp;·&nbsp; Filed {{ $c->created_at->format('M j, Y') }}
        </div>
        <div class="mt-2" style="font-size:13px; color:#555;">{{ $c->description }}</div>
        @if($c->resolution_notes)
        <div class="mt-2 p-2" style="background:#f8f9fc; border-left:3px solid #009643; border-radius:6px; font-size:12.5px; color:#444;">
            <strong style="color:#009643;">Resolution:</strong> {{ $c->resolution_notes }}
        </div>
        @endif
    </div>
    @empty
    <div class="text-center py-4 mb-4" style="background:#f8f9fc; border-radius:12px; border:1px dashed #ddd;">
        <p class="text-muted mb-0" style="font-size:13px;">You haven't filed any complaints.</p>
    </div>
    @endforelse

    <h6 style="font-weight:700; color:#555; margin-top:28px; margin-bottom:14px;">
        <i class="fas fa-exclamation-triangle mr-2"></i> Filed About Your Sessions ({{ $against->count() }})
    </h6>

    @forelse($against as $c)
    <div class="complaint-card p-3" style="opacity:.9;">
        <div class="d-flex align-items-center flex-wrap mb-1" style="gap:8px;">
            <span style="font-weight:700; font-size:14px; color:#1a1a2e;">{{ $c->subject }}</span>
            <span class="status-badge status-{{ $c->status }}">{{ ucfirst(str_replace('_',' ',$c->status)) }}</span>
        </div>
        <div style="font-size:12px; color:#9e9e9e;">
            Ref: <strong style="color:#666;">{{ $c->reference_number }}</strong>
            &nbsp;·&nbsp; Filed by {{ $c->counselee?->full_name ?? 'Counselee' }} on {{ $c->created_at->format('M j, Y') }}
        </div>
        <div class="mt-2" style="font-size:13px; color:#555;">{{ $c->description }}</div>
    </div>
    @empty
    <div class="text-center py-4" style="background:#f8f9fc; border-radius:12px; border:1px dashed #ddd;">
        <p class="text-muted mb-0" style="font-size:13px;">No complaints have been filed about your sessions.</p>
    </div>
    @endforelse

</div>
@endsection
