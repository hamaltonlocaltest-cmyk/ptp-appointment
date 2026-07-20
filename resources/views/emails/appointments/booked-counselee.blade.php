<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Booked</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; color:#333;">
<div class="wrap" style="max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(211,4,4,0.12);">
    
	 <div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>
	
    <div class="hdr" style="background:linear-gradient(135deg,#2e8a44,#256f37); padding:32px 30px 28px; text-align:center;">
        <div class="hdr-icon" style="width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; line-height:1; color:#ffffff;">&#10003;</div>
        <h1 style="color:#fff; margin:0; font-size:21px; font-weight:700;">Appointment Booked</h1>
        <p style="color:#ffcccc; margin:6px 0 0; font-size:13px;">P2P Counselling — Appointment Confirmation</p>
    </div>

    <div class="body" style="padding:30px 30px 26px; color:#333; line-height:1.6;">
        <h2 style="color:#2e8a44; margin:0 0 6px; font-size:17px; font-weight:700;">Hello, {{ $appointment->counselee->first_name }}!</h2>
        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">Your appointment has been successfully submitted. Here are your booking details:</p>

        <div class="detail-box" style="background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0;">
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Counselling Area</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselType->name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Your Counselor</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselor->full_name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Date</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Time</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->formatted_time }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Mode</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->mode }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:none; font-size:13.5px; align-items:center;">
                <span class="detail-label" style="color:#888; font-weight:600;">Status</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">
                    <span class="status-pill" style="display:inline-block; background:#fff3cd; color:#856404; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700;">Pending Confirmation</span>
                </span>
            </div>
        </div>

        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">Your counselor will confirm the appointment shortly. You will receive another email once confirmed.</p>

        <div class="btn-wrap" style="text-align:center; margin:26px 0 20px;">
            <a href="{{ route('counselee.appointments.index') }}" class="btn" style="display:inline-block; background:linear-gradient(135deg,#2e8a44,#256f37); color:#fff !important; text-decoration:none; padding:13px 32px; border-radius:10px; font-size:14.5px; font-weight:700; ">View My Appointments</a>
        </div>

        @if($appointment->notes)
        <p style="font-size:13px; color:#777; margin:0 0 18px 0; line-height:1.5;"><strong>Your notes:</strong> {{ $appointment->notes }}</p>
        @endif

        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">If you need to cancel, please do so at least 24 hours in advance through your appointments page.</p>
        <p style="margin:0; font-size:14px; color:#333;">Warm regards,<br><strong style="color:#2e8a44;">P2P Counselling Team</strong></p>
    </div>

    <div class="ftr" style="background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee;">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>

</div>
</body>
</html>