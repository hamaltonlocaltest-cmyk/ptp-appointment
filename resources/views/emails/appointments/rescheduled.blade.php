<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.hdr { background:#4a148c; padding:28px 30px; text-align:center; }
.hdr h1 { color:#fff; margin:0; font-size:22px; }
.hdr p  { color:#e1bee7; margin:5px 0 0; font-size:13px; }
.body { padding:28px 30px; color:#333; line-height:1.6; }
.body h2 { color:#4a148c; margin-top:0; font-size:18px; }
.compare { display:flex; gap:12px; margin:18px 0; }
.compare-col { flex:1; border-radius:8px; padding:16px 18px; }
.compare-old { background:#fdecea; border:1px solid #f5c6cb; }
.compare-new { background:#e8f5e9; border:1px solid #a5d6a7; }
.compare-label { font-size:11px; text-transform:uppercase; letter-spacing:.5px; font-weight:700; margin-bottom:8px; }
.compare-old .compare-label { color:#c62828; }
.compare-new .compare-label { color:#1b5e20; }
.compare-row { font-size:13px; margin-bottom:4px; color:#333; }
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
        <h1>&#8635; Appointment Rescheduled</h1>
        <p>P2P Counselling — Reschedule Notice</p>
    </div>
    <div class="body">
        <h2>Hello,</h2>
        <p>The appointment below has been rescheduled{{ $reschedule->rescheduled_by ? ' by the ' . $reschedule->rescheduled_by : '' }} and is confirmed for the new date and time:</p>

        <div class="compare">
            <div class="compare-col compare-old">
                <div class="compare-label">Previous</div>
                <div class="compare-row">{{ $reschedule->old_appointment_date->format('D, M j, Y') }}</div>
                <div class="compare-row">{{ $reschedule->formatted_old_time }}</div>
                <div class="compare-row">{{ optional($reschedule->oldCounselor)->full_name ?? '—' }}</div>
            </div>
            <div class="compare-col compare-new">
                <div class="compare-label">New</div>
                <div class="compare-row">{{ $reschedule->new_appointment_date->format('D, M j, Y') }}</div>
                <div class="compare-row">{{ $reschedule->formatted_new_time }}</div>
                <div class="compare-row">{{ optional($reschedule->newCounselor)->full_name ?? '—' }}</div>
            </div>
        </div>

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
                <span class="detail-label">Status</span>
                <span class="detail-value">Confirmed</span>
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
