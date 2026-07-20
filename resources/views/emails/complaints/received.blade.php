<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Complaint Received</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; color:#333;">
<div class="wrap" style="max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(0,0,0,0.1);">

    <div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>
    <div class="hdr" style="background:linear-gradient(135deg,#2e8a44,#256f37); padding:32px 30px 28px; text-align:center;">
        <div class="hdr-icon" style="width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; line-height:1;">
            <div class="hdr-icon">&#128172;</div>
        </div>
        <h1 style="color:#fff; margin:0; font-size:20px; font-weight:700;">We've Received Your Complaint</h1>
        <p style="color:#ffe0b2; margin:6px 0 0; font-size:13px;">P2P Counselling — Support Team</p>
    </div>

    <div class="body" style="padding:30px 30px 26px; color:#333; line-height:1.6;">
        <h2 style="color:#2e8a44; margin:0 0 6px; font-size:17px; font-weight:700;">Thank you for letting us know</h2>
        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">We've logged your complaint and our team will review it shortly. Please keep the reference number below for any follow-up.</p>

        <div class="ref-box" style="background:#effbf0; border:1.5px dashed #2e8a44; border-radius:12px; padding:16px 22px; margin:18px 0; text-align:center;">
            <div class="ref-label" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#a05a00; font-weight:700;">Reference Number</div>
            <div class="ref-value" style="font-size:20px; font-weight:800; color:#2e8a44; margin-top:4px;">{{ $complaint->reference_number }}</div>
        </div>

        <div class="detail-box" style="background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0;">
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Subject</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $complaint->subject }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Status</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ ucfirst(str_replace('_', ' ', $complaint->status)) }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:none; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Filed On</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $complaint->created_at->format('M j, Y g:i A') }}</span>
            </div>
        </div>

        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">We aim to review every complaint promptly. You'll receive another email once there's an update.</p>

        <p style="margin:0; font-size:14px; color:#333;">Warm regards,<br><strong style="color:#2e8a44;">P2P Counselling Team</strong></p>
    </div>

    <div class="ftr" style="background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee;">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>

</div>
</body>
</html>