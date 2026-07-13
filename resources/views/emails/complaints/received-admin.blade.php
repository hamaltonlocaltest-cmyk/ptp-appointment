<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family:'Segoe UI',Arial,sans-serif; background:#eef0f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 6px 28px rgba(198,40,40,0.12); }
.hdr { background:linear-gradient(135deg, #c62828, #b71c1c); padding:32px 30px 28px; text-align:center; }
.hdr-icon { width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,0.18); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:22px; }
.hdr h1 { color:#fff; margin:0; font-size:20px; font-weight:700; }
.hdr p  { color:#ffcdd2; margin:6px 0 0; font-size:13px; }
.body { padding:30px 30px 26px; color:#333; line-height:1.6; }
.body h2 { color:#c62828; margin:0 0 6px; font-size:17px; font-weight:700; }
.detail-box { background:#f9f9fb; border:1px solid #ececf2; border-radius:12px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; gap:12px; padding:7px 0; border-bottom:1px solid #eee; font-size:13.5px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#888; font-weight:600; }
.detail-value { color:#222; font-weight:700; text-align:right; }
.desc-box { background:#fdecea; border-left:3px solid #c62828; border-radius:0 8px 8px 0; padding:15px 18px; margin:18px 0; color:#444; font-size:13.5px; line-height:1.6; }
.btn-wrap { text-align:center; margin:26px 0 6px; }
.btn { display:inline-block; background:linear-gradient(135deg, #c62828, #b71c1c); color:#fff !important; text-decoration:none; padding:13px 32px; border-radius:10px; font-size:14.5px; font-weight:700; box-shadow:0 6px 16px rgba(198,40,40,0.25); }
.ftr { background:#f9f9fb; text-align:center; padding:20px; font-size:11.5px; color:#a0a0a8; border-top:1px solid #eee; }
@media (max-width:600px) { .wrap { margin:0; border-radius:0; } .hdr, .body, .ftr { padding-left:20px; padding-right:20px; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <div class="hdr-icon">&#9888;</div>
        <h1>New Complaint Filed</h1>
        <p>{{ $complaint->reference_number }}</p>
    </div>
    <div class="body">
        <h2>A new complaint needs review</h2>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Filed By</span>
                <span class="detail-value">
                    {{ ucfirst($complaint->filed_by) }} —
                    {{ $complaint->filed_by === 'counselor' ? $complaint->counselor?->full_name : $complaint->counselee?->full_name }}
                </span>
            </div>
            @if($complaint->appointment)
            <div class="detail-row">
                <span class="detail-label">Related Session</span>
                <span class="detail-value">{{ $complaint->appointment->counselType->name }} — {{ $complaint->appointment->appointment_date->format('M j, Y') }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Subject</span>
                <span class="detail-value">{{ $complaint->subject }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Filed On</span>
                <span class="detail-value">{{ $complaint->created_at->format('M j, Y g:i A') }}</span>
            </div>
        </div>

        <div class="desc-box">
            {{ $complaint->description }}
        </div>

        <div class="btn-wrap">
            <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn">Review Complaint</a>
        </div>
    </div>
    <div class="ftr">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>
</div>
</body>
</html>
