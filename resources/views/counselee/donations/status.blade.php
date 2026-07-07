@extends($layout)
@section('title', 'Donation Status')
@section('page-title', 'Donation Status')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('counselee.donations.create') }}">Donate</a></li>
    <li class="breadcrumb-item active">Status</li>
@endsection

@section('content')
<div class="container py-4">
<div class="row justify-content-center">
<div class="col-lg-5">

    <div class="card text-center" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">

            @if($donation->status === 'completed')
                <div class="icon-circle" style="background:#e8f5e9; color:#1b5e20;"><i class="fas fa-check"></i></div>
                <h4 style="font-weight:700; color:#1b5e20;">Thank You!</h4>
                <p class="text-muted" style="font-size:13.5px;">Your donation was successful. A receipt has been sent to your email.</p>
            @elseif($donation->status === 'failed')
                <div class="icon-circle" style="background:#fdecea; color:#c62828;"><i class="fas fa-times"></i></div>
                <h4 style="font-weight:700; color:#c62828;">Payment Failed</h4>
                <p class="text-muted" style="font-size:13.5px;">Your payment couldn't be completed. No amount was charged. Please try again.</p>
            @else
                <div class="icon-circle" style="background:#fff3cd; color:#856404;"><i class="fas fa-hourglass-half"></i></div>
                <h4 style="font-weight:700; color:#856404;">Payment Pending</h4>
                <p class="text-muted" style="font-size:13.5px;">We're still confirming your payment status with the gateway. This can take a few minutes.</p>
            @endif

            <div class="detail-box">
                <div class="detail-row"><span class="detail-label">Amount</span><span class="detail-value">{{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}</span></div>
                <div class="detail-row"><span class="detail-label">Donor</span><span class="detail-value">{{ $donation->donor_display_name }}</span></div>
                @if($donation->payment_reference)
                <div class="detail-row"><span class="detail-label">Reference</span><span class="detail-value">{{ $donation->payment_reference }}</span></div>
                @endif
            </div>

            @if($donation->status === 'failed')
            <a href="{{ route('counselee.donations.create') }}" class="btn" style="background:#1b5e20; color:#fff; border-radius:7px; padding:10px 26px; font-weight:700;">
                Try Again
            </a>
            @endif
        </div>
    </div>

</div>
</div>
</div>

<style>
.icon-circle { width:72px; height:72px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 18px; font-size:30px; }
.detail-box { background:#f8f9fc; border-radius:8px; padding:16px 20px; margin:18px 0; text-align:left; font-size:13.5px; }
.detail-row { display:flex; justify-content:space-between; padding:5px 0; }
.detail-label { color:#888; }
.detail-value { font-weight:700; color:#222; }
</style>
@endsection
