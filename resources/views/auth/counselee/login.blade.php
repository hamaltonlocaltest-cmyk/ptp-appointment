<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselee — Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-page { background: #4a148c; }
        .login-logo a { color: #fff; font-size: 1.6rem; }
        .login-logo span { color: #ce93d8; }
        .card { border-radius: 10px; border: none; box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
        .btn-purple { background: #4a148c; border-color: #4a148c; color: #fff; }
        .btn-purple:hover { background: #6a1b9a; border-color: #6a1b9a; color: #fff; }
        .role-badge { text-align:center; margin-bottom: 10px; }
        .role-badge span { background:#4a148c; color:#fff; font-size:11px; padding:3px 14px; border-radius:20px; letter-spacing:1px; text-transform:uppercase; }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>P2P</b> <span>Appointment</span></a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <div class="role-badge"><span><i class="fas fa-user"></i> Counselee</span></div>
            <p class="login-box-msg text-muted">Sign in to your account</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            @if (session('message'))
                <div class="alert alert-success py-2">{{ session('message') }}</div>
            @endif

            <form action="{{ route('counselee.login') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email address" value="{{ old('email') }}" autofocus required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-purple btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i> Sign In
                        </button>
                    </div>
                </div>
            </form>

            <p class="mb-1 mt-3 text-center">
                <a href="{{ route('counselee.register') }}" style="color:#4a148c;">Create an account</a>
            </p>
            <div class="mt-2 text-center" style="font-size:12px; color:#aaa;">
                <a href="{{ route('admin.login') }}">Admin Login</a> &nbsp;|&nbsp;
                <a href="{{ route('counselor.login') }}">Counselor Login</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
