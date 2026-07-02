<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselee — Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .register-page { background: #4a148c; }
        .register-logo a { color: #fff; font-size: 1.6rem; }
        .register-logo span { color: #ce93d8; }
        .card { border-radius: 10px; border: none; box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
        .btn-purple { background: #4a148c; border-color: #4a148c; color: #fff; }
        .btn-purple:hover { background: #6a1b9a; border-color: #6a1b9a; color: #fff; }
        .role-badge { text-align:center; margin-bottom: 10px; }
        .role-badge span { background:#4a148c; color:#fff; font-size:11px; padding:3px 14px; border-radius:20px; letter-spacing:1px; text-transform:uppercase; }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="#"><b>P2P</b> <span>Appointment</span></a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <div class="role-badge"><span><i class="fas fa-user"></i> Counselee Registration</span></div>
            <p class="login-box-msg text-muted">Create your account</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('counselee.register') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name"
                                   value="{{ old('first_name') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                                   value="{{ old('last_name') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email address"
                           value="{{ old('email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number"
                           value="{{ old('phone') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="date" name="birthdate" class="form-control"
                                   value="{{ old('birthdate') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <select name="gender" class="form-control" required>
                                <option value="" disabled selected>Gender</option>
                                <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-venus-mars"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-purple btn-block">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </button>
                    </div>
                </div>
            </form>

            <p class="mb-0 mt-3 text-center">
                Already have an account? <a href="{{ route('counselee.login') }}" style="color:#4a148c;">Sign in</a>
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
