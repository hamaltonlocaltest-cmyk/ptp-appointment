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
.detail-box { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:8px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#777; font-weight:600; }
.detail-value { color:#222; font-weight:700; }
.stars { color:#f9a825; font-size:20px; letter-spacing:2px; }
.comment-box { background:#f3e9ff; border-left:3px solid #4a148c; border-radius:6px; padding:14px 18px; margin:18px 0; font-style:italic; color:#444; }
.ftr { background:#f0f0f0; text-align:center; padding:14px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>&#9733; New Feedback Received</h1>
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
