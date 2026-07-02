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
.detail-box { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:8px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#777; font-weight:600; }
.detail-value { color:#222; font-weight:700; }
.status-pill { display:inline-block; background:#e8f5e9; color:#1b5e20; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.btn-wrap { text-align:center; margin:24px 0; }
.btn { display:inline-block; background:#D30404; color:#fff !important; text-decoration:none; padding:12px 30px; border-radius:6px; font-size:15px; font-weight:700; }
.ftr { background:#f0f0f0; text-align:center; padding:14px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>&#10003; Session Completed</h1>
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
            <a href="{{ route('counselee.appointments.index') }}" class="btn">View My Appointments</a>
        </div>

        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
