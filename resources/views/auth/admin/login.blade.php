<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — P2P Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body.login-page {
           background: url("{{ asset('images/bg-counselor-login.webp') }}") no-repeat center center; background-size:cover;		position: relative;
            /*min-height: 100vh;*/
        }				.logo-wrap {  background: #fff;  padding: 10px;  border-radius: 10px;}
        .login-box { width: 420px; }
        .login-logo { margin-bottom: 20px; }
        .login-logo a { color: #fff; font-size: 28px; font-weight: 700; text-decoration: none; }
        .login-logo a span { color: #FFFFC4 }
        .login-logo small { display: block; color: rgba(255,255,255); font-size: 14px; font-weight: 400; margin-top: 4px; letter-spacing: 0.5px; }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-body { padding: 36px 32px; }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e8eaf6;
            color: #1a237e;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .login-title { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .login-sub { font-size: 13px; color: #9e9e9e; margin-bottom: 24px; }
        .form-label { font-size: 12px; font-weight: 600; color: #555; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .input-group .form-control {
            border-right: none;
            border-radius: 8px 0 0 8px !important;
            border: 1.5px solid #e0e0e0;
            padding: 10px 14px;
            font-size: 14px;
            height: auto;
        }
        .input-group .form-control:focus {
            border-color: #1a237e;
            box-shadow: none;
        }
        .input-group-text {
            border-radius: 0 8px 8px 0 !important;
            border: 1.5px solid #e0e0e0;
            border-left: none;
            background: #f8f9fc;
            color: #9e9e9e;
            padding: 0 14px;
        }
        .input-group:focus-within .input-group-text {
            border-color: #1a237e;
        }
        .btn-login {
            background: #D30404;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            width: 100%;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .btn-login:hover {
            background: #00B24F;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,35,126,0.4);
        }
        .alert-danger {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            color: #c53030;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
        .other-portals {
            border-top: 1px solid #f0f0f0;
            margin-top: 24px;
            padding-top: 16px;
            text-align: center;
        }
        .other-portals p { font-size: 14px;  color: #444;  margin-bottom: 10px; }
        .portal-links { display: flex; justify-content: center; gap: 10px; }
        .portal-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            padding: 5px 12px;
            border-radius: 20px;
            text-decoration: none;
            border: 1px solid #e0e0e0;
            color: #666;
            transition: all 0.2s;
        }				.portal-link.btn1 {  background: #00B24F;  color: #fff;  border-color: #00B24F;  font-size: 16px;  font-weight: 600;}.portal-link.btn1:hover{background: #D30404; border-color:#D30404; color:#fff;}.portal-link.btn2 {  background: #D30404;  color: #fff;  border-color: #D30404;  font-size: 16px;  font-weight: 600;}.portal-link.btn2:hover{background: #00B24F; border-color:#00B24F; color:#fff;}
        .portal-link:hover { border-color: #1a237e; color: #1a237e; text-decoration: none; }
        .powered { text-align: center;  margin-top: 20px;  font-size: 13px;  color: #fff;}
		@media (max-width: 576px) {
		  .login-box, .register-box {
			margin-top: .5rem;
			width: auto !important;
		  }
		  .logo-wrap{max-width:220px;}
		}
    </style>
</head>
<body class="hold-transition login-page">
<div class="logo-wrap"><img src="https://persontoperson.org/wp-content/uploads/2020/06/persontoperson-logo-300x52.png" class="img-fluid"></div>
<div class="login-box">
    <div class="login-logo text-center">
        <a href="#"><b>P2P</b> <span>Appointment</span></a>
        <small>Counseling Management System</small>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <span class="role-badge">
                    <i class="fas fa-shield-alt"></i> Super Admin Portal
                </span>
            </div>
            <p class="login-title text-center">Welcome Back</p>
            <p class="login-sub text-center">Sign in to your admin account</p>

            @if($errors->any())
                <div class="alert-danger">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first() }}
                </div>
            @endif

            @if(session('message'))
                <div class="alert alert-success" style="border-radius:8px; font-size:13px; padding:10px 14px;">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <input type="email" name="email"
                               class="form-control"
                               placeholder="admin@example.com"
                               value="{{ old('email') }}"
                               autofocus required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                               class="form-control"
                               placeholder="Enter your password"
                               required>
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePw()" style="cursor:pointer;">
                                <i class="fas fa-eye" id="pw-icon"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In to Admin Panel
                </button>
            </form>

            <div class="other-portals">
                <p>Other portals</p>
                <div class="portal-links">
                    <a href="{{ route('counselor.login') }}" class="portal-link btn1">
                        <i class="fas fa-user-md"></i> Counselor
                    </a>
                    <a href="{{ route('counselee.login') }}" class="portal-link btn2">
                        <i class="fas fa-user"></i> Counselee
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="powered">&copy; {{ date('Y') }} P2P Appointment System</div>
</div>

<script>
function togglePw() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('pw-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>