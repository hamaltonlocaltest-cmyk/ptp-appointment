<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Registration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #eef0f4; color: #333; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 28px rgba(27,94,32,0.12); }
        .header { background: linear-gradient(135deg, #1b5e20, #2e7d32); padding: 38px 30px 32px; text-align: center; }
        .header-icon { width: 54px; height: 54px; border-radius: 50%; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 24px; }
        .header h1 { color: #fff; font-size: 24px; font-weight: 700; letter-spacing: .3px; }
        .header p { color: #a5d6a7; font-size: 13.5px; margin-top: 6px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.18); color: #fff; font-size: 11px; padding: 5px 16px; border-radius: 20px; margin-top: 14px; letter-spacing: .8px; text-transform: uppercase; font-weight: 600; }
        .body { padding: 34px 30px; }
        .greeting { font-size: 18px; font-weight: 700; color: #1b5e20; margin-bottom: 10px; }
        .intro { font-size: 14px; color: #555; line-height: 1.7; margin-bottom: 24px; }
        .credentials-box { background: #f2f9f0; border: 1px solid #c5e1a5; border-radius: 12px; padding: 22px 24px; margin-bottom: 22px; }
        .credentials-box h3 { font-size: 12.5px; text-transform: uppercase; letter-spacing: 1px; color: #558b2f; margin-bottom: 16px; font-weight: 700; }
        .cred-row { display: flex; align-items: center; margin-bottom: 12px; }
        .cred-row:last-child { margin-bottom: 0; }
        .cred-label { font-size: 12.5px; color: #6b7b6b; width: 100px; flex-shrink: 0; font-weight: 600; }
        .cred-value { font-size: 14px; font-weight: 700; color: #1b5e20; background: #fff; border: 1px solid #c5e1a5; border-radius: 6px; padding: 7px 12px; flex: 1; font-family: 'Courier New', monospace; letter-spacing: 0.4px; word-break: break-all; }
        .login-btn { display: block; text-align: center; background: linear-gradient(135deg, #1b5e20, #2e7d32); color: #fff; text-decoration: none; padding: 14px 30px; border-radius: 10px; font-size: 15px; font-weight: 700; margin: 24px 0; box-shadow: 0 6px 16px rgba(27,94,32,0.25); }
        .login-url { background: #f9f9fb; border: 1px dashed #c3d8c3; border-radius: 8px; padding: 11px 15px; font-size: 12.5px; color: #555; margin-bottom: 20px; word-break: break-all; }
        .login-url span { font-weight: 700; color: #1b5e20; }
        .warning-box { background: #fff8e1; border-left: 4px solid #ffc107; border-radius: 0 8px 8px 0; padding: 13px 16px; margin-bottom: 20px; font-size: 13px; color: #795548; line-height: 1.6; }
        .steps { margin-bottom: 22px; }
        .steps h4 { font-size: 12.5px; color: #555; margin-bottom: 12px; text-transform: uppercase; letter-spacing: .5px; font-weight: 700; }
        .step { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px; font-size: 13px; color: #555; line-height: 1.5; }
        .step-num { width: 22px; height: 22px; background: #1b5e20; color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .divider { border: none; border-top: 1px solid #eee; margin: 22px 0; }
        .footer { background: #f9f9fb; padding: 22px 30px; text-align: center; border-top: 1px solid #eee; }
        .footer p { font-size: 11.5px; color: #a0a0a8; line-height: 1.8; }
        .footer a { color: #1b5e20; text-decoration: none; font-weight: 600; }
        @media (max-width: 600px) {
            .wrapper { margin: 0; border-radius: 0; }
            .header, .body, .footer { padding-left: 20px; padding-right: 20px; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="header-icon">&#128274;</div>
        <h1>P2P Appointment</h1>
        <p>Counseling Management System</p>
        <span class="badge">&#10003; Counselor Account Created</span>
    </div>

    <div class="body">
        <p class="greeting">Hello, {{ $fullName }}!</p>
        <p class="intro">
            Your counselor account has been successfully created on the <strong>P2P Appointment System</strong>.
            Below are your login credentials. Please keep them safe and confidential.
        </p>

        <div class="credentials-box">
            <h3>&#128274; Your Login Credentials</h3>
            <div class="cred-row">
                <span class="cred-label">Full Name</span>
                <span class="cred-value">{{ $fullName }}</span>
            </div>
            <div class="cred-row">
                <span class="cred-label">Email</span>
                <span class="cred-value">{{ $email }}</span>
            </div>
            <div class="cred-row">
                <span class="cred-label">Password</span>
                <span class="cred-value">{{ $plainPassword }}</span>
            </div>
        </div>

        <div class="warning-box">
            &#9888; For your security, please change your password immediately after your first login.
        </div>

        <div class="login-url">
            <span>Login URL:</span> {{ url('/counselor/login') }}
        </div>

        <a href="{{ url('/counselor/login') }}" class="login-btn">
            Login to Your Account &rarr;
        </a>

        <div class="steps">
            <h4>Getting Started</h4>
            <div class="step">
                <div class="step-num">1</div>
                <span>Visit the login URL above and sign in with your credentials.</span>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <span>Complete your profile with your photo and specialization details.</span>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <span>Change your password from your account settings.</span>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <span>Start managing appointments with your counselees.</span>
            </div>
        </div>

        <hr class="divider">
        <p style="font-size:13px; color:#888;">
            If you did not create this account or believe this was a mistake, please contact us immediately.
        </p>
    </div>

    <div class="footer">
        <p>
            &copy; {{ date('Y') }} P2P Appointment System. All rights reserved.<br>
            This is an automated email — please do not reply directly.<br>
            <a href="{{ url('/') }}">Visit our website</a>
        </p>
    </div>

</div>
</body>
</html>
