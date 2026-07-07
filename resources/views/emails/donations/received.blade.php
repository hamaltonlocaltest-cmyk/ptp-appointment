<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.hdr { background:#1b5e20; padding:28px 30px; text-align:center; }
.hdr h1 { color:#fff; margin:0; font-size:22px; }
.hdr p  { color:#c8e6c9; margin:5px 0 0; font-size:13px; }
.body { padding:28px 30px; color:#333; line-height:1.6; }
.body h2 { color:#1b5e20; margin-top:0; font-size:18px; }
.amount-box { background:#e8f5e9; border-radius:8px; padding:20px; margin:18px 0; text-align:center; }
.amount-label { font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#2e7d32; font-weight:700; }
.amount-value { font-size:32px; font-weight:800; color:#1b5e20; margin-top:4px; }
.detail-box { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:8px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#777; font-weight:600; }
.detail-value { color:#222; font-weight:700; }
.ftr { background:#f0f0f0; text-align:center; padding:14px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>&#10084; Thank You for Your Donation</h1>
        <p>P2P Counselling</p>
    </div>
    <div class="body">
        <h2>Hello, {{ $donation->donor_display_name }}!</h2>
        <p>Your generosity helps us keep counselling accessible for everyone who needs it. Thank you for your support.</p>

        <div class="amount-box">
            <div class="amount-label">Amount Donated</div>
            <div class="amount-value">{{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}</div>
        </div>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Payment Reference</span>
                <span class="detail-value">{{ $donation->payment_reference }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $donation->updated_at->format('M j, Y g:i A') }}</span>
            </div>
        </div>

        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
