<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselee Registration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #333; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #4a148c, #6a1b9a); padding: 40px 30px; text-align: center; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 700; letter-spacing: 1px; }
        .header p { color: #ce93d8; font-size: 14px; margin-top: 6px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.2); color: #fff; font-size: 11px; padding: 4px 14px; border-radius: 20px; margin-top: 10px; letter-spacing: 1px; text-transform: uppercase; }
        .body { padding: 35px 30px; }
        .greeting { font-size: 18px; font-weight: 600; color: #4a148c; margin-bottom: 12px; }
        .intro { font-size: 14px; color: #555; line-height: 1.7; margin-bottom: 25px; }
        .credentials-box { background: #f3e5f5; border: 1px solid #ce93d8; border-radius: 8px; padding: 20px 24px; margin-bottom: 25px; }
        .credentials-box h3 { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #6a1b9a; margin-bottom: 16px; }
        .cred-row { display: flex; align-items: center; margin-bottom: 12px; }
        .cred-row:last-child { margin-bottom: 0; }
        .cred-label { font-size: 13px; color: #777; width: 110px; flex-shrink: 0; }
        .cred-value { font-size: 14px; font-weight: 600; color: #4a148c; background: #fff; border: 1px solid #ce93d8; border-radius: 5px; padding: 6px 12px; flex: 1; font-family: 'Courier New', monospace; letter-spacing: 0.5px; }
        .login-btn { display: block; text-align: center; background: #4a148c; color: #fff; text-decoration: none; padding: 14px 30px; border-radius: 8px; font-size: 15px; font-weight: 600; margin: 25px 0; }
        .login-url { background: #f9f9f9; border: 1px dashed #ccc; border-radius: 6px; padding: 10px 15px; font-size: 13px; color: #555; margin-bottom: 20px; word-break: break-all; }
        .login-url span { font-weight: 600; color: #4a148c; }
        .warning-box { background: #fff8e1; border-left: 4px solid #ffc107; border-radius: 0 6px 6px 0; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; color: #795548; line-height: 1.6; }
        .steps { margin-bottom: 20px; }
        .steps h4 { font-size: 13px; color: #555; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .step { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 8px; font-size: 13px; color: #555; line-height: 1.5; }
        .step-num { width: 22px; height: 22px; background: #4a148c; color: #fff; border-radius: 50%; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
        .footer { background: #f9f9f9; padding: 20px 30px; text-align: center; border-top: 1px solid #eee; }
        .footer p { font-size: 12px; color: #aaa; line-height: 1.7; }
        .footer a { color: #4a148c; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>P2P Appointment</h1>
        <p>Counseling Management System</p>
        <span class="badge">&#10003; Account Successfully Created</span>
    </div>

    <div class="body">
        <p class="greeting">Hello, {{ $fullName }}!</p>
        <p class="intro">
            Welcome to the <strong>P2P Appointment System</strong>! Your account has been successfully created.
            You can now book appointments with our professional counselors.
            Below are your login credentials — please keep them private.
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
            <span>Login URL:</span> {{ url('/counselee/login') }}
        </div>

        <a href="{{ url('/counselee/login') }}" class="login-btn">
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
                <span>Complete your profile with your personal information.</span>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <span>Change your password from your account settings.</span>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <span>Browse available counselors and book your first appointment.</span>
            </div>
        </div>

        <hr class="divider">
        <p style="font-size:13px; color:#888;">
            If you did not register for this account, please contact us immediately so we can secure your information.
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
