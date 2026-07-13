<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(27,94,32,0.12); }
.hdr { background:linear-gradient(135deg, #1b5e20, #2e7d32); padding:32px 30px 28px; text-align:center; }
.hdr-icon { width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; }
.hdr h1 { color:#fff; margin:0; font-size:20px; font-weight:700; }
.hdr p  { color:#c8e6c9; margin:6px 0 0; font-size:13px; }
.body { padding:30px 30px 26px; color:#333; line-height:1.6; }
.body h2 { color:#1b5e20; margin:0 0 6px; font-size:17px; font-weight:700; }
.amount-box { background:#e8f5e9; border-radius:12px; padding:22px; margin:18px 0; text-align:center; }
.amount-label { font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#2e7d32; font-weight:700; }
.amount-value { font-size:32px; font-weight:800; color:#1b5e20; margin-top:4px; }
.detail-box { background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#888; font-weight:600; }
.detail-value { color:#222; font-weight:700; text-align:right; }
.ftr { background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee; }
@media (max-width:600px) { .wrap { margin:0; border-radius:0; } .hdr, .body, .ftr { padding-left:20px; padding-right:20px; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <div class="hdr-icon">&#10084;</div>
        <h1>Thank You for Your Donation</h1>
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
