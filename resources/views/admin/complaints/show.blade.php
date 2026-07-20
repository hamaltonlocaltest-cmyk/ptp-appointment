@extends('admin.layouts.app')
@section('title', 'Complaint Details')
@section('page-title', 'Complaint Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.complaints.index') }}">Complaints</a></li>
    <li class="breadcrumb-item active">{{ $complaint->reference_number }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <div style="font-size:12px; color:#9e9e9e;">Reference Number</div>
                    <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">{{ $complaint->reference_number }}</h4>
                </div>
                @php
                $badgeColor = ['open'=>'badge-pending','in_review'=>'badge-pending','resolved'=>'badge-active','closed'=>'badge-inactive'][$complaint->status] ?? 'badge-pending';
                @endphp
                <span class="{{ $badgeColor }}" style="font-size:13px; padding:6px 16px;">
                    {{ ucfirst(str_replace('_',' ',$complaint->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header" style="background:#fff;">
            <span style="font-weight:600;"><i class="fas fa-info-circle mr-2"></i> Complaint Information</span>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Filed By</div>
                    <div style="font-weight:600; text-transform:capitalize;">{{ $complaint->filed_by }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Counselee</div>
                    <div style="font-weight:600;">{{ $complaint->counselee?->full_name ?? '—' }}</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Counselor</div>
                    <div style="font-weight:600;">{{ $complaint->counselor?->full_name ?? '—' }}</div>
                </div>
                @if($complaint->appointment)
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Related Session</div>
                    <div style="font-weight:600;">{{ $complaint->appointment->counselType->name }} — {{ $complaint->appointment->appointment_date->format('M j, Y') }}</div>
                </div>
                @endif
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Filed On</div>
                    <div style="font-weight:600;">{{ $complaint->created_at->format('M j, Y g:i A') }}</div>
                </div>
                @if($complaint->resolved_at)
                <div class="col-md-4 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Resolved On</div>
                    <div style="font-weight:600;">{{ $complaint->resolved_at->format('M j, Y g:i A') }}</div>
                </div>
                @endif
            </div>

            <div class="mt-2">
                <div style="font-size:12px; color:#9e9e9e; margin-bottom:6px;">Subject</div>
                <div style="font-weight:700; font-size:15px; color:#1a1a2e;">{{ $complaint->subject }}</div>
            </div>
            <div class="mt-3">
                <div style="font-size:12px; color:#9e9e9e; margin-bottom:6px;">Description</div>
                <div class="p-3" style="background:#f8f9fc; border-radius:8px; font-size:13.5px; color:#444;">
                    {{ $complaint->description }}
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header" style="background:#fff;">
            <span style="font-weight:600;"><i class="fas fa-edit mr-2"></i> Update Status</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        @foreach(['open' => 'Open', 'in_review' => 'In Review', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $value => $label)
                        <option value="{{ $value }}" {{ $complaint->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Resolution Notes</label>
                    <textarea name="resolution_notes" rows="4" class="form-control"
                              placeholder="Explain how this was resolved or what action was taken...">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary action-btn mb-3 mb-sm-0" style="border:1px solid #e0e4ec;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary action-btn mb-3 mb-sm-0">
                        <i class="fas fa-save mr-1"></i> Save Update
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</div>
@endsection
