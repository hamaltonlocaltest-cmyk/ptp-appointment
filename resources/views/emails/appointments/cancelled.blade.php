<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Cancelled</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; color:#333;">
<div class="wrap" style="max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(198,40,40,0.12);">
    
	<div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>
	
    <div class="hdr" style="background:linear-gradient(135deg, #c62828, #b71c1c); padding:32px 30px 28px; text-align:center;">
        <div class="hdr-icon" style="width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; line-height:1; color:#ffffff;">&#10005;</div>
        <h1 style="color:#fff; margin:0; font-size:21px; font-weight:700;">Appointment Cancelled</h1>
        <p style="color:#ffcdd2; margin:6px 0 0; font-size:13px;">P2P Counselling — Cancellation Notice</p>
    </div>

    <div class="body" style="padding:30px 30px 26px; color:#333; line-height:1.6;">
        <h2 style="color:#c62828; margin:0 0 6px; font-size:17px; font-weight:700;">Hello,</h2>
        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">The following appointment has been cancelled{{ $appointment->cancelled_by ? ' by the ' . $appointment->cancelled_by : '' }}:</p>

        <div class="detail-box" style="background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0;">
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Counsellee</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselee->full_name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Counselor</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselor->full_name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Counselling Area</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselType->name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Date</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Time</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->formatted_time }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:none; font-size:13.5px; align-items:center;">
                <span class="detail-label" style="color:#888; font-weight:600;">Status</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">
                    <span class="status-pill" style="display:inline-block; background:#fdecea; color:#c62828; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700;">Cancelled</span>
                </span>
            </div>
        </div>

        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">If this was a mistake or you'd like to book a new session, you're welcome to do so anytime.</p>
        <p style="margin:0; font-size:14px; color:#333;">Warm regards,<br><strong style="color:#c62828;">P2P Counselling Team</strong></p>
    </div>

    <div class="ftr" style="background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee;">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>

</div>
</body>
</html>