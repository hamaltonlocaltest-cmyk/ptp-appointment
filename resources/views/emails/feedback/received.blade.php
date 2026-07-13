<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(74,20,140,0.12); }
.hdr { background:linear-gradient(135deg, #4a148c, #6a1b9a); padding:32px 30px 28px; text-align:center; }
.hdr-icon { width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; }
.hdr h1 { color:#fff; margin:0; font-size:21px; font-weight:700; }
.hdr p  { color:#e1bee7; margin:6px 0 0; font-size:13px; }
.body { padding:30px 30px 26px; color:#333; line-height:1.6; }
.body h2 { color:#4a148c; margin:0 0 6px; font-size:17px; font-weight:700; }
.detail-box { background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#888; font-weight:600; }
.detail-value { color:#222; font-weight:700; text-align:right; }
.stars { color:#f9a825; font-size:19px; letter-spacing:2px; }
.comment-box { background:#f3e9ff; border-left:3px solid #4a148c; border-radius:0 8px 8px 0; padding:15px 18px; margin:18px 0; font-style:italic; color:#444; font-size:13.5px; line-height:1.6; }
.ftr { background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee; }
@media (max-width:600px) { .wrap { margin:0; border-radius:0; } .hdr, .body, .ftr { padding-left:20px; padding-right:20px; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <div class="hdr-icon">&#9733;</div>
        <h1>New Feedback Received</h1>
        <p>P2P Counselling</p>
    </div>
    <div class="body">
        <h2>Hello, {{ $feedback->counselor->first_name }}!</h2>
        <p>{{ $feedback->counselee->full_name }} left feedback for their recent session with you.</p>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Counselling Area</span>
                <span class="detail-value">{{ $feedback->appointment->counselType->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Session Date</span>
                <span class="detail-value">{{ $feedback->appointment->appointment_date->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Rating</span>
                <span class="detail-value stars">{{ str_repeat('★', $feedback->rating) }}{{ str_repeat('☆', 5 - $feedback->rating) }}</span>
            </div>
        </div>

        @if($feedback->comments)
        <div class="comment-box">
            "{{ $feedback->comments }}"
        </div>
        @endif

        <p>Warm regards,<br><strong>P2P Counselling Team</strong></p>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
