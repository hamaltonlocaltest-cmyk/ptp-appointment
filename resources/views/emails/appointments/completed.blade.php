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
.hdr h1 { color:#fff; margin:0; font-size:21px; font-weight:700; }
.hdr p  { color:#c8e6c9; margin:6px 0 0; font-size:13px; }
.body { padding:30px 30px 26px; color:#333; line-height:1.6; }
.body h2 { color:#1b5e20; margin:0 0 6px; font-size:17px; font-weight:700; }
.detail-box { background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#888; font-weight:600; }
.detail-value { color:#222; font-weight:700; text-align:right; }
.status-pill { display:inline-block; background:#e8f5e9; color:#1b5e20; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.btn-wrap { text-align:center; margin:26px 0 6px; }
.btn { display:inline-block; background:linear-gradient(135deg, #D30404, #b71c1c); color:#fff !important; text-decoration:none; padding:13px 32px; border-radius:10px; font-size:14.5px; font-weight:700; box-shadow:0 6px 16px rgba(211,4,4,0.22); }
.link-row { text-align:center; margin-bottom:18px; }
.link-row a { color:#1b5e20; font-size:13px; font-weight:600; text-decoration:none; }
.ftr { background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee; }
@media (max-width:600px) { .wrap { margin:0; border-radius:0; } .hdr, .body, .ftr { padding-left:20px; padding-right:20px; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <div class="hdr-icon">&#10003;</div>
        <h1>Session Completed</h1>
        <p>P2P Counselling — Thank You</p>
    </div>
    <div class="body">
        <h2>Hello, {{ $appointment->counselee->first_name }}!</h2>
        <p>Your counselling session has been marked as completed. Thank you for attending.</p>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Counselling Area</span>
                <span class="detail-value">{{ $appointment->counselType->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Counselor</span>
                <span class="detail-value">{{ $appointment->counselor->full_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value"><span class="status-pill">Completed</span></span>
            </div>
        </div>

        <p>We hope the session was helpful. You're welcome to book a follow-up appointment at any time.</p>

        <div class="btn-wrap">
            <a href="{{ route('counselee.appointments.feedback.create', $appointment) }}" class="btn">&#9733; Leave Feedback</a>
        </div>
        <div class="link-row">
            <a href="{{ route('counselee.appointments.index') }}">View My Appointments</a>
        </div>

        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
