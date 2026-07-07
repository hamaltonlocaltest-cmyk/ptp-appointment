<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'P2P Appointment') — P2P Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background:#f4f6f9; font-family: Arial, sans-serif; }
        .guest-header {
            background:#fff; border-bottom:1px solid #e8eaf0; padding:14px 24px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .guest-header a.brand { color:#1a1a2e; font-weight:800; font-size:17px; text-decoration:none; }
        .guest-header a.brand i { color:#D30404; margin-right:8px; }
        .guest-nav a { color:#666; font-size:13px; font-weight:600; text-decoration:none; margin-left:18px; }
        .guest-nav a:hover { color:#D30404; }
        .guest-page-title { padding:20px 24px 0; }
        .guest-page-title h1 { font-size:20px; font-weight:700; color:#1a1a2e; margin:0; }
        .guest-page-title .breadcrumb { background:transparent; padding:6px 0 0; margin:0; font-size:12px; }
        .guest-content { padding:24px; }
        .guest-footer { background:#fff; border-top:1px solid #e8eaf0; text-align:center; padding:14px; font-size:12px; color:#888; margin-top:30px; }
    </style>
    @stack('styles')
</head>
<body>

<div class="guest-header">
    <a href="{{ route('landing') }}" class="brand"><i class="fas fa-hand-holding-heart"></i>P2P Counselling</a>
    <div class="guest-nav">
        <a href="{{ route('landing') }}">Home</a>
        <a href="{{ route('counselee.login') }}">Counselee Login</a>
        <a href="{{ route('counselor.login') }}">Counselor Login</a>
    </div>
</div>

<div class="guest-page-title">
    <h1>@yield('page-title')</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('landing') }}" style="color:#D30404;">Home</a></li>
        @yield('breadcrumb')
    </ol>
</div>

<div class="guest-content">
    @yield('content')
</div>

<div class="guest-footer">
    &copy; {{ date('Y') }} <strong>P2P Appointment System</strong>. All rights reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
