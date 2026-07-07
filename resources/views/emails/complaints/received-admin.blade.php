<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
body { font-family:Arial,sans-serif; background:#f4f4f4; margin:0; padding:0; }
.wrap { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.1); }
.hdr { background:#c62828; padding:28px 30px; text-align:center; }
.hdr h1 { color:#fff; margin:0; font-size:22px; }
.hdr p  { color:#ffcdd2; margin:5px 0 0; font-size:13px; }
.body { padding:28px 30px; color:#333; line-height:1.6; }
.body h2 { color:#c62828; margin-top:0; font-size:18px; }
.detail-box { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:8px; padding:18px 22px; margin:18px 0; }
.detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
.detail-row:last-child { border-bottom:none; }
.detail-label { color:#777; font-weight:600; }
.detail-value { color:#222; font-weight:700; text-align:right; }
.desc-box { background:#fdecea; border-left:3px solid #c62828; border-radius:6px; padding:14px 18px; margin:18px 0; color:#444; }
.btn-wrap { text-align:center; margin:24px 0; }
.btn { display:inline-block; background:#c62828; color:#fff !important; text-decoration:none; padding:12px 30px; border-radius:6px; font-size:15px; font-weight:700; }
.ftr { background:#f0f0f0; text-align:center; padding:14px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>&#9888; New Complaint Filed</h1>
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
