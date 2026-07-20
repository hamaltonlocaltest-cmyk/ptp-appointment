<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donation Receipt</title>
</head>

<body style="margin:0;padding:30px;background:#f4f6f8;font-family:'Segoe UI',Arial,sans-serif;">

	


<div style="max-width:620px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.08);">
<div style="height:20px;"></div>
<div style="text-align:center;">
		<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
	</div>
	<div style="height:20px;"></div>
	
    <!-- Header -->
    <div style="background:linear-gradient(135deg,#2e8a44,#256f37);padding:35px 30px;text-align:center;">

        

        <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">
            Thank You for Your Donation
        </h1>

        <p style="margin:8px 0 0;color:#d9f2df;font-size:14px;">
            P2P Counselling
        </p>

    </div>

    <!-- Body -->
    <div style="padding:35px 30px;color:#1f2937;font-size:15px;line-height:1.7;">

        <h2 style="margin:0 0 10px;font-size:20px;color:#2e8a44;">
            Hello, {{ $donation->donor_display_name }}!
        </h2>

        <p style="margin:0 0 25px;color:#4b5563;">
            Your generosity helps us keep counselling accessible for everyone who needs it. Thank you for your support.
        </p>

        <!-- Amount -->
        <div style="background:#f4fbf6;border:2px solid #d8eedf;border-radius:12px;padding:25px;text-align:center;margin-bottom:25px;">

            <div style="font-size:12px;font-weight:700;color:#2e8a44;letter-spacing:1px;text-transform:uppercase;">
                Amount Donated
            </div>

            <div style="margin-top:8px;font-size:34px;font-weight:800;color:#dc2426;">
                {{ $donation->currency }} {{ number_format((float) $donation->amount, 2) }}
            </div>

        </div>

        <!-- Details -->
        <div style="background:#fafafa;border:1px solid #e5e7eb;border-radius:12px;padding:20px;">

            <div style="padding:12px 0;border-bottom:1px solid #ececec;overflow:hidden;">
                <span style="float:left;font-weight:600;color:#6b7280;">Payment Reference</span>
                <span style="float:right;font-weight:700;color:#1f2937;">
                    {{ $donation->payment_reference }}
                </span>
            </div>

            <div style="padding:12px 0;overflow:hidden;">
                <span style="float:left;font-weight:600;color:#6b7280;">Date</span>
                <span style="float:right;font-weight:700;color:#1f2937;">
                    {{ $donation->updated_at->format('M j, Y g:i A') }}
                </span>
            </div>

        </div>

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