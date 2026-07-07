<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.hdr { background:#e65100; padding:28px 30px; text-align:center; }
.hdr h1 { color:#fff; margin:0; font-size:22px; }
.hdr p  { color:#ffe0b2; margin:5px 0 0; font-size:13px; }
.body { padding:28px 30px; color:#333; line-height:1.6; }
.body h2 { color:#e65100; margin-top:0; font-size:18px; }
.ref-box { background:#fff3e0; border:1px dashed #e65100; border-radius:8px; padding:16px 22px; margin:18px 0; text-align:center; }
.ref-label { font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#a05a00; font-weight:700; }
.ref-value { font-size:20px; font-weight:800; color:#e65100; margin-top:4px; }
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
        <h1>We've Received Your Complaint</h1>
        <p>P2P Counselling — Support Team</p>
    </div>
    <div class="body">
        <h2>Thank you for letting us know</h2>
        <p>We've logged your complaint and our team will review it shortly. Please keep the reference number below for any follow-up.</p>

        <div class="ref-box">
            <div class="ref-label">Reference Number</div>
            <div class="ref-value">{{ $complaint->reference_number }}</div>
        </div>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Subject</span>
                <span class="detail-value">{{ $complaint->subject }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Filed On</span>
                <span class="detail-value">{{ $complaint->created_at->format('M j, Y g:i A') }}</span>
            </div>
        </div>

        <p>We aim to review every complaint promptly. You'll receive another email once there's an update.</p>

        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
