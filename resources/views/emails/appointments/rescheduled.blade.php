<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Rescheduled</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; color:#333;">
<div class="wrap" style="max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(74,20,140,0.12);">
    <div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>
    <div class="hdr" style="background:linear-gradient(135deg,#2e8a44,#256f37); padding:32px 30px 28px; text-align:center;">
        <div class="hdr-icon" style="width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; line-height:1; color:#ffffff;">&#8635;</div>
        <h1 style="color:#fff; margin:0; font-size:21px; font-weight:700;">Appointment Rescheduled</h1>
        <p style="color:#fff; margin:6px 0 0; font-size:13px;">P2P Counselling — Reschedule Notice</p>
    </div>

    <div class="body" style="padding:30px 30px 26px; color:#333; line-height:1.6;">
        <h2 style="color:#2e8a44; margin:0 0 6px; font-size:17px; font-weight:700;">Hello,</h2>
        <p style="margin:0 0 18px 0; font-size:14px; color:#333;">The appointment below has been rescheduled{{ $reschedule->rescheduled_by ? ' by the ' . $reschedule->rescheduled_by : '' }} and is confirmed for the new date and time:</p>

        <!-- Robust, table-based responsive comparison layout for email clients -->
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 18px 0;">
            <tr>
                <td class="compare-col-cell" width="48%" valign="top" style="background:#fdecea; border:1px solid #f5c6cb; border-radius:12px; padding:16px 18px;">
                    <div class="compare-label" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px; font-weight:700; margin-bottom:8px; color:#c62828;">Previous</div>
                    <div class="compare-row" style="font-size:13px; margin-bottom:4px; color:#333;">{{ $reschedule->old_appointment_date->format('D, M j, Y') }}</div>
                    <div class="compare-row" style="font-size:13px; margin-bottom:4px; color:#333;">{{ $reschedule->formatted_old_time }}</div>
                    <div class="compare-row" style="font-size:13px; margin-bottom:0; color:#333;">{{ optional($reschedule->oldCounselor)->full_name ?? '—' }}</div>
                </td>
                <td width="4%">&nbsp;</td>
                <td class="compare-col-cell" width="48%" valign="top" style="background:#e8f5e9; border:1px solid #a5d6a7; border-radius:12px; padding:16px 18px;">
                    <div class="compare-label" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px; font-weight:700; margin-bottom:8px; color:#1b5e20;">New</div>
                    <div class="compare-row" style="font-size:13px; margin-bottom:4px; color:#333;">{{ $reschedule->new_appointment_date->format('D, M j, Y') }}</div>
                    <div class="compare-row" style="font-size:13px; margin-bottom:4px; color:#333;">{{ $reschedule->formatted_new_time }}</div>
                    <div class="compare-row" style="font-size:13px; margin-bottom:0; color:#333;">{{ optional($reschedule->newCounselor)->full_name ?? '—' }}</div>
                </td>
            </tr>
        </table>

        <div class="detail-box" style="background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0;">
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Counsellee</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselee->full_name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Counselling Area</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $appointment->counselType->name }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:none; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Status</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">Confirmed</span>
            </div>
        </div>

        <p style="margin:0; font-size:14px; color:#333;">Warm regards,<br><strong style="color:#2e8a44;">P2P Counselling Team</strong></p>
    </div>

    <div class="ftr" style="background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee;">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>

</div>
</body>
</html>