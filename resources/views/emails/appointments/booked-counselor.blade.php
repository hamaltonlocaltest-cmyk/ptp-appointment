<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.hdr { background:#1a237e; padding:28px 30px; text-align:center; }
.hdr h1 { color:#fff; margin:0; font-size:22px; }
.hdr p  { color:#c5cae9; margin:5px 0 0; font-size:13px; }
.body { padding:28px 30px; color:#333; line-height:1.6; }
.body h2 { color:#1a237e; margin-top:0; font-size:18px; }
.detail-box { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:8px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#777; font-weight:600; }
.detail-value { color:#222; font-weight:700; }
.alert-box { background:#fff3cd; border:1px solid #ffc107; border-radius:8px; padding:14px 18px; margin:18px 0; font-size:14px; color:#856404; }
.ftr { background:#f0f0f0; text-align:center; padding:14px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>📅 New Appointment Request</h1>
        <p>P2P Counselling — Counselor Notification</p>
    </div>
    <div class="body">
        <h2>Hello, {{ $appointment->counselor->first_name }}!</h2>
        <p>You have a new appointment request. Please review the details below:</p>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Counsellee</span>
                <span class="detail-value">{{ $appointment->counselee->full_name }}</span>
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
                <span class="detail-label">Mode</span>
                <span class="detail-value">{{ $appointment->mode }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Counsellee Contact</span>
                <span class="detail-value">{{ $appointment->counselee->email }}</span>
            </div>
        </div>

        @if($appointment->notes)
        <div class="alert-box">
            <strong>Note from counsellee:</strong> {{ $appointment->notes }}
        </div>
        @endif

        <p>Please log in to your counselor dashboard to confirm or manage this appointment.</p>
        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
