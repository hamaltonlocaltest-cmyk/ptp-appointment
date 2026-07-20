<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Complaint Filed</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; color:#333;">
<div class="wrap" style="max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(198,40,40,0.12);">
    <div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>
    <div class="hdr" style="background:linear-gradient(135deg,#2e8a44,#256f37); padding:32px 30px 28px; text-align:center;">
        <div class="hdr-icon" style="width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; line-height:1;">
             <div style="color:#fff;">&#9888;</div>
        </div>
        <h1 style="color:#fff; margin:0; font-size:20px; font-weight:700;">New Complaint Filed</h1>
        <p style="color:#ffcdd2; margin:6px 0 0; font-size:13px;">{{ $complaint->reference_number }}</p>
    </div>

    <div class="body" style="padding:30px 30px 26px; color:#333; line-height:1.6;">
        <h2 style="color:#c62828; margin:0 0 6px; font-size:17px; font-weight:700;">A new complaint needs review</h2>

        <div class="detail-box" style="background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0;">
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Filed By</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">
                    {{ ucfirst($complaint->filed_by) }} —
                    {{ $complaint->filed_by === 'counselor' ? $complaint->counselor?->full_name : $complaint->counselee?->full_name }}
                </span>
            </div>
            @if($complaint->appointment)
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Related Session</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $complaint->appointment->counselType->name }} — {{ $complaint->appointment->appointment_date->format('M j, Y') }}</span>
            </div>
            @endif
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Subject</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $complaint->subject }}</span>
            </div>
            <div class="detail-row" style="display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:none; font-size:13.5px;">
                <span class="detail-label" style="color:#888; font-weight:600;">Filed On</span>
                <span class="detail-value" style="color:#222; font-weight:700; text-align:right;">{{ $complaint->created_at->format('M j, Y g:i A') }}</span>
            </div>
        </div>

        <div class="desc-box" style="background:#fdecea; border-left:3px solid #c62828; border-radius:0 8px 8px 0; padding:15px 18px; margin:18px 0; color:#444; font-size:13.5px; line-height:1.6;">
            {{ $complaint->description }}
        </div>

        <div class="btn-wrap" style="text-align:center; margin:26px 0 6px;">
            <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn" style="display:inline-block; background:linear-gradient(135deg, #c62828, #b71c1c); color:#fff !important; text-decoration:none; padding:13px 32px; border-radius:10px; font-size:14.5px; font-weight:700; box-shadow:0 6px 16px rgba(198,40,40,0.25);">Review Complaint</a>
        </div>
    </div>

    <div class="ftr" style="background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee;">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>

</div>
</body>
</html>