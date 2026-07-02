<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor Dashboard — P2P Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-sidebar { background: linear-gradient(180deg, #1b3a1f 0%, #1b5e20 100%) !important; }
        .brand-link { background: rgba(0,0,0,0.2) !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .brand-text b { color: #a5d6a7; }
        .nav-sidebar .nav-link { border-radius: 6px !important; margin: 2px 8px !important; color: rgba(255,255,255,0.75) !important; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: rgba(255,255,255,0.15) !important; color: #fff !important; }
        .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.5) !important; }
        .nav-sidebar .nav-link.active .nav-icon { color: #a5d6a7 !important; }
        .sidebar-heading { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.35); padding: 12px 20px 4px; display: block; }
        .user-panel { padding: 12px 16px !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .user-panel .info a { color: #fff !important; font-size: 14px; font-weight: 500; }
        .user-panel .info small { color: rgba(255,255,255,0.45); font-size: 11px; }
        .content-wrapper { background: #f4f6f9 !important; }
        .content-header h1 { font-size: 20px; font-weight: 700; color: #1b5e20; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .card-header { border-bottom: 1px solid #f0f2f5; padding: 14px 20px; }
        .stat-card { border-radius: 12px; padding: 22px 20px; color: #fff; position: relative; overflow: hidden; }
        .stat-card h3 { font-size: 34px; font-weight: 700; margin: 0 0 4px; }
        .stat-card p { font-size: 13px; margin: 0; opacity: 0.85; }
        .stat-card .stat-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 52px; opacity: 0.12; }
        .main-footer { background: #fff; border-top: 1px solid #e8eaf0; color: #aaa; font-size: 12px; }
        .avatar-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; flex-shrink: 0; }
        .badge-active   { background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-pending  { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item mr-3">
                <span style="font-size:13px; color:#555;">
                    <i class="fas fa-circle text-success mr-1" style="font-size:8px;"></i>
                    {{ $counselor->full_name }}
                </span>
            </li>
            <li class="nav-item">
                <form action="{{ route('counselor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:20px; font-size:12px; padding:5px 14px;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar elevation-0">
        <a href="{{ route('counselor.dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light"><b>P2P</b> Counselor</span>
        </a>
        <div class="sidebar">
            <div class="user-panel d-flex align-items-center">
                <div class="image">
                    <div class="avatar-circle" style="background:#2e7d32; font-size:14px;">
                        {{ strtoupper(substr($counselor->first_name, 0, 1)) }}
                    </div>
                </div>
                <div class="info ml-2">
                    <a href="#" class="d-block">{{ $counselor->full_name }}</a>
                    <small>{{ $counselor->specialization }}</small>
                </div>
            </div>
            <nav class="mt-2 pb-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('counselor.dashboard') }}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <span class="sidebar-heading">Appointments</span>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>My Appointments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>Pending Requests</p>
                        </a>
                    </li>
                    <span class="sidebar-heading">Account</span>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>My Profile</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">

                @if(session('message'))
                    <div class="alert" style="background:#e8f5e9; color:#1b5e20; border:none; border-radius:8px; font-size:13px; padding:10px 16px;">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
                    </div>
                @endif

                {{-- Welcome Banner --}}
                <div class="card mb-4" style="background: linear-gradient(135deg, #1b5e20, #2e7d32); color:#fff; border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle mr-3" style="background:rgba(255,255,255,0.2); width:56px; height:56px; font-size:22px;">
                                {{ strtoupper(substr($counselor->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 style="margin:0; font-weight:700;">Welcome, {{ $counselor->first_name }}!</h4>
                                <p style="margin:4px 0 0; opacity:0.8; font-size:13px;">
                                    <i class="fas fa-briefcase-medical mr-1"></i> {{ $counselor->specialization }}
                                    &nbsp;&nbsp;
                                    <i class="fas fa-envelope mr-1"></i> {{ $counselor->email }}
                                </p>
                            </div>
                            <div class="ml-auto">
                                @if($counselor->status === 'active')
                                    <span class="badge-active">Active</span>
                                @else
                                    <span class="badge-pending">{{ ucfirst($counselor->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stat Cards --}}
                <div class="row mb-3">
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
                            <h3>0</h3>
                            <p>Total Appointments</p>
                            <i class="fas fa-calendar-check stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
                            <h3>0</h3>
                            <p>Pending Requests</p>
                            <i class="fas fa-clock stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="stat-card" style="background:linear-gradient(135deg,#1565c0,#1976d2);">
                            <h3>0</h3>
                            <p>Completed Sessions</p>
                            <i class="fas fa-check-double stat-icon"></i>
                        </div>
                    </div>
                </div>

                {{-- Upcoming Appointments Placeholder --}}
                <div class="card">
                    <div class="card-header" style="background:#fff;">
                        <span style="color:#1b5e20; font-size:15px; font-weight:600;">
                            <i class="fas fa-calendar-alt mr-2"></i> Upcoming Appointments
                        </span>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-alt" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-0">No upcoming appointments yet.</p>
                        <small class="text-muted">Appointments booked by counselees will appear here.</small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="main-footer text-center">
        &copy; {{ date('Y') }} <strong>P2P Appointment System</strong>. All rights reserved.
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>