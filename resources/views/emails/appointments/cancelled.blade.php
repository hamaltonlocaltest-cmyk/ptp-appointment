<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(198,40,40,0.12); }
.hdr { background:linear-gradient(135deg, #c62828, #b71c1c); padding:32px 30px 28px; text-align:center; }
.hdr-icon { width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; }
.hdr h1 { color:#fff; margin:0; font-size:21px; font-weight:700; }
.hdr p  { color:#ffcdd2; margin:6px 0 0; font-size:13px; }
.body { padding:30px 30px 26px; color:#333; line-height:1.6; }
.body h2 { color:#c62828; margin:0 0 6px; font-size:17px; font-weight:700; }
.detail-box { background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#888; font-weight:600; }
.detail-value { color:#222; font-weight:700; text-align:right; }
.status-pill { display:inline-block; background:#fdecea; color:#c62828; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.ftr { background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee; }
@media (max-width:600px) { .wrap { margin:0; border-radius:0; } .hdr, .body, .ftr { padding-left:20px; padding-right:20px; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <div class="hdr-icon">&#10005;</div>
        <h1>Appointment Cancelled</h1>
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
