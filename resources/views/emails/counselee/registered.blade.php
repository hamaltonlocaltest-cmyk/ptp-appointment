<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselee Registration</title>
</head>
<body style="margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; background: #eef0f4; color: #333;">
<div class="wrapper" style="max-width: 600px; margin: 30px auto; background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 28px rgba(74,20,140,0.12);">
<div style="height:20px;"></div>
<div style="text-align:center;">
			<img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" style="max-width:auto; width:280px; ">
		</div>
		<div style="height:20px;"></div>

    <div class="header" style="background: linear-gradient(135deg,#2e8a44,#256f37); padding: 38px 30px 32px; text-align: center;">

        <h1 style="color: #fff; font-size: 24px; font-weight: 700; letter-spacing: .3px; margin: 0;">P2P Appointment</h1>
        <p style="color: #fff; font-size: 13.5px; margin-top: 6px; margin-bottom: 0;">Counseling Management System</p>
        <span class="badge" style="display: inline-block; background: rgba(255,255,255,0.18); color: #fff; font-size: 11px; padding: 5px 16px; border-radius: 20px; margin-top: 14px; letter-spacing: .8px; text-transform: uppercase; font-weight: 600;">&#10003; Account Successfully Created</span>
    </div>

    <div class="body" style="padding: 34px 30px;">
        <p class="greeting" style="font-size: 18px; font-weight: 700; color: rgb(28, 137, 83); margin-top: 0; margin-bottom: 10px;">Hello, {{ $fullName }}!</p>
        <p class="intro" style="font-size: 14px; color: #555; line-height: 1.7; margin-top: 0; margin-bottom: 24px;">
            Welcome to the <strong>P2P Appointment System</strong>! Your account has been successfully created.
            You can now book appointments with our professional counselors.
            Below are your login credentials — please keep them private.
        </p>

        <div class="credentials-box" style="background: #f0fbf0; border: 1px solid #ccf3cc; border-radius: 12px; padding: 22px 24px; margin-bottom: 22px;">
            <h3 style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 1px; color: #2e8a44; margin-top: 0; margin-bottom: 16px; font-weight: 700;">&#128274; Your Login Credentials</h3>
            <div class="cred-row" style="display: flex; align-items: center; margin-bottom: 12px;">
                <span class="cred-label" style="font-size: 12.5px; color: #7b6b85; width: 100px; flex-shrink: 0; font-weight: 600;">Full Name</span>
                <span class="cred-value" style="font-size: 14px; font-weight: 700; color: rgb(28, 137, 83); background: #fff; border: 1px solid #ccf3cc; border-radius: 6px; padding: 7px 12px; flex: 1; font-family: 'Courier New', monospace; letter-spacing: 0.4px; word-break: break-all;">{{ $fullName }}</span>
            </div>
            <div class="cred-row" style="display: flex; align-items: center; margin-bottom: 12px;">
                <span class="cred-label" style="font-size: 12.5px; color: #7b6b85; width: 100px; flex-shrink: 0; font-weight: 600;">Email</span>
                <span class="cred-value" style="font-size: 14px; font-weight: 700; color: rgb(28, 137, 83); background: #fff; border: 1px solid #ccf3cc; border-radius: 6px; padding: 7px 12px; flex: 1; font-family: 'Courier New', monospace; letter-spacing: 0.4px; word-break: break-all;">{{ $email }}</span>
            </div>
            <div class="cred-row" style="display: flex; align-items: center; margin-bottom: 0;">
                <span class="cred-label" style="font-size: 12.5px; color: #7b6b85; width: 100px; flex-shrink: 0; font-weight: 600;">Password</span>
                <span class="cred-value" style="font-size: 14px; font-weight: 700; color: rgb(28, 137, 83); background: #fff; border: 1px solid #ccf3cc; border-radius: 6px; padding: 7px 12px; flex: 1; font-family: 'Courier New', monospace; letter-spacing: 0.4px; word-break: break-all;">{{ $plainPassword }}</span>
            </div>
        </div>

        <div class="warning-box" style="background: #fff8e1; border-left: 4px solid #ffc107; border-radius: 0 8px 8px 0; padding: 13px 16px; margin-bottom: 20px; font-size: 13px; color: #795548; line-height: 1.6;">
            &#9888; For your security, please change your password immediately after your first login.
        </div>

        <div class="login-url" style="background: #f9f9fb; border: 1px dashed #d0c3d8; border-radius: 8px; padding: 11px 15px; font-size: 12.5px; color: #555; margin-bottom: 20px; word-break: break-all;">
            <span style="font-weight: 700; color: rgb(28, 137, 83);">Login URL:</span> {{ url('/counselee/login') }}
        </div>

        <a href="{{ url('/counselee/login') }}" class="login-btn" style="display: block; text-align: center; background: linear-gradient(135deg,#2e8a44,#256f37); color: #fff; text-decoration: none; padding: 14px 30px; border-radius: 10px; font-size: 15px; font-weight: 700; margin: 24px 0; box-shadow: 0 6px 16px rgba(74,20,140,0.25);">
            Login to Your Account &rarr;
        </a>

        <div class="steps" style="margin-bottom: 22px;">
            <h4 style="font-size: 12.5px; color: #555; margin-top: 0; margin-bottom: 12px; text-transform: uppercase; letter-spacing: .5px; font-weight: 700;">Getting Started</h4>
            <div class="step" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #555; line-height: 1.5;">
                <div class="step-num" style="width: 22px; height: 22px; background: rgb(28, 137, 83); color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">1</div>
                <span>Visit the login URL above and sign in with your credentials.</span>
            </div>
            <div class="step" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #555; line-height: 1.5;">
                <div class="step-num" style="width: 22px; height: 22px; background: rgb(28, 137, 83); color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">2</div>
                <span>Complete your profile with your personal information.</span>
            </div>
            <div class="step" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #555; line-height: 1.5;">
                <div class="step-num" style="width: 22px; height: 22px; background: rgb(28, 137, 83); color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">3</div>
                <span>Change your password from your account settings.</span>
            </div>
            <div class="step" style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #555; line-height: 1.5;">
                <div class="step-num" style="width: 22px; height: 22px; background: rgb(28, 137, 83); color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">4</div>
                <span>Browse available counselors and book your first appointment.</span>
            </div>
        </div>

        <hr class="divider" style="border: none; border-top: 1px solid #eee; margin: 22px 0;">
        <p style="font-size:13px; color:#888; margin-top: 0; margin-bottom: 0;">
            If you did not register for this account, please contact us immediately so we can secure your information.
        </p>
    </div>

    <div class="footer" style="background: #f9f9fb; padding: 22px 30px; text-align: center; border-top: 1px solid #eee;">
        <p style="font-size: 11.5px; color: #a0a0a8; line-height: 1.8; margin-top: 0; margin-bottom: 0;">
            &copy; {{ date('Y') }} P2P Appointment System. All rights reserved.<br>
            This is an automated email — please do not reply directly.<br>
            <a href="{{ url('/') }}" style="color: rgb(28, 137, 83); text-decoration: none; font-weight: 600;">Visit our website</a>
        </p>
    </div>

</div>
</body>
</html>