<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.hdr { background:#c62828; padding:28px 30px; text-align:center; }
.hdr h1 { color:#fff; margin:0; font-size:22px; }
.hdr p  { color:#ffcdd2; margin:5px 0 0; font-size:13px; }
.body { padding:28px 30px; color:#333; line-height:1.6; }
.body h2 { color:#c62828; margin-top:0; font-size:18px; }
.detail-box { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:8px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#777; font-weight:600; }
.detail-value { color:#222; font-weight:700; }
.status-pill { display:inline-block; background:#fdecea; color:#c62828; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.ftr { background:#f0f0f0; text-align:center; padding:14px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>&#10005; Appointment Cancelled</h1>
        <p>P2P Counselling — Cancellation Notice</p>
    </div>
    <div class="body">
        <h2>Hello,</h2>
        <p>The following appointment has been cancelled{{ $appointment->cancelled_by ? ' by the ' . $appointment->cancelled_by : '' }}:</p>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Counsellee</span>
                <span class="detail-value">{{ $appointment->counselee->full_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Counselor</span>
                <span class="detail-value">{{ $appointment->counselor->full_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Counselling Area</span>
                <span class="detail-value">{{ $appointment->counselType->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value">{{ $appointment->formatted_time }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value"><span class="status-pill">Cancelled</span></span>
            </div>
        </div>

        <p>If this was a mistake or you'd like to book a new session, you're welcome to do so anytime.</p>
        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
