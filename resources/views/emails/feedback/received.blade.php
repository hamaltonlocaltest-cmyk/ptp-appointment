<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback Received</title>
</head>

<body style="margin:0;padding:30px;background:#f4f6f8;font-family:'Segoe UI',Arial,sans-serif;">

		
<div style="max-width:620px;margin:0 auto;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e5e7eb;box-shadow:0 8px 30px rgba(0,0,0,.08);">
<div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>

    <!-- Header -->
    <div style="background:linear-gradient(135deg,#2e8a44,#256f37);padding:15px 30px;text-align:center;">
		
		
        
		
        <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">
            New Feedback Received
        </h1>
		
		
        
    </div>

    <!-- Body -->
    <div style="padding:35px 30px;color:#1f2937;font-size:15px;line-height:1.7;">

        <h2 style="margin:0 0 10px;font-size:20px;color:#2e8a44;">
            Hello, {{ $feedback->counselor->first_name }}!
        </h2>

        <p style="margin:0 0 25px;color:#4b5563;">
            {{ $feedback->counselee->full_name }} left feedback for their recent session with you.
        </p>

        <!-- Details -->
        <div style="background:#fafafa;border:1px solid #e5e7eb;border-radius:12px;padding:20px;">

            <div style="padding:12px 0;border-bottom:1px solid #ececec;overflow:hidden;">
                <span style="float:left;font-weight:600;color:#6b7280;">Counselling Area</span>
                <span style="float:right;font-weight:700;color:#1f2937;">{{ $feedback->appointment->counselType->name }}</span>
            </div>

            <div style="padding:12px 0;border-bottom:1px solid #ececec;overflow:hidden;">
                <span style="float:left;font-weight:600;color:#6b7280;">Session Date</span>
                <span style="float:right;font-weight:700;color:#1f2937;">{{ $feedback->appointment->appointment_date->format('l, F j, Y') }}</span>
            </div>

            <div style="padding:12px 0;overflow:hidden;">
                <span style="float:left;font-weight:600;color:#6b7280;">Rating</span>
                <span style="float:right;font-size:18px;color:#dc2426;font-weight:bold;">
                    {{ str_repeat('★', $feedback->rating) }}{{ str_repeat('☆', 5 - $feedback->rating) }}
                </span>
            </div>

        </div>

        @if($feedback->comments)

        <div style="margin-top:25px;background:#f4fbf6;border-left:5px solid #2e8a44;padding:18px 20px;border-radius:8px;color:#374151;font-style:italic;line-height:1.7;">
            "{{ $feedback->comments }}"
        </div>

        @endif

        <p style="margin-top:30px;color:#1f2937;">
            Warm regards,<br>
            <strong style="color:#2e8a44;">P2P Counselling Team</strong>
        </p>

    </div>

    <!-- Footer -->
    <div style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:18px;text-align:center;font-size:12px;color:#6b7280;">
        &copy; {{ date('Y') }} Person to Person Counselling. All rights reserved.
    </div>

</div>

</body>
</html>