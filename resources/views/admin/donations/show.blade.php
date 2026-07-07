@extends('admin.layouts.app')
@section('title', 'Donation Details')
@section('page-title', 'Donation Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.donations.index') }}">Donations</a></li>
    <li class="breadcrumb-item active">#{{ $donation->id }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <div style="font-size:12px; color:#9e9e9e;">Amount</div>
                    <h3 style="color:#1b5e20; font-weight:800; margin:0;">{{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}</h3>
                </div>
                @if($donation->status === 'completed')
                    <span class="badge-active" style="font-size:13px; padding:6px 16px;">Completed</span>
                @elseif($donation->status === 'failed')
                    <span class="badge-inactive" style="font-size:13px; padding:6px 16px;">Failed</span>
                @else
                    <span class="badge-pending" style="font-size:13px; padding:6px 16px;">Pending</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header" style="background:#fff;">
            <span style="font-weight:600;"><i class="fas fa-info-circle mr-2"></i> Donation Information</span>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Donor</div>
                    <div style="font-weight:600;">{{ $donation->donor_display_name }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Email</div>
                    <div style="font-weight:600;">{{ $donation->counselee->email ?? $donation->donor_email ?? '—' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Payment Reference</div>
                    <div style="font-weight:600;">{{ $donation->payment_reference ?: '—' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Instamojo Payment Request ID</div>
                    <div style="font-weight:600; font-size:12.5px;">{{ $donation->instamojo_payment_request_id ?: '—' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div style="font-size:12px; color:#9e9e9e;">Received On</div>
                    <div style="font-weight:600;">{{ $donation->created_at->format('M j, Y g:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.donations.index') }}" class="btn btn-light" style="border:1px solid #e0e4ec;">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        @if($donation->status === 'pending')
        <form action="{{ route('admin.donations.complete', $donation) }}" method="POST" id="completeForm">
            @csrf
            <button type="button" class="btn" style="background:#1b5e20; color:#fff;" data-toggle="modal" data-target="#completeModal">
                <i class="fas fa-check mr-1"></i> Mark as Completed
            </button>
        </form>
        @endif
    </div>

</div>
</div>

<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#e8f5e9; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-check" style="font-size:24px; color:#1b5e20;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Mark donation as completed?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    Use this only after confirming payment of
                    <strong style="color:#1a1a2e;">{{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}</strong>
                    from <strong style="color:#1a1a2e;">{{ $donation->donor_display_name }}</strong> was received outside of Instamojo
                    (e.g. bank transfer). The donor will get a receipt email.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Cancel
                </button>
                <button type="button" class="btn flex-fill" onclick="document.getElementById('completeForm').submit();"
                        style="background:#1b5e20; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-check mr-1"></i> Yes, Mark Completed
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
