<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselee Dashboard — P2P Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-sidebar { background: linear-gradient(180deg, #2a0a4a 0%, #4a148c 100%) !important; }
        .brand-link { background: rgba(0,0,0,0.2) !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .brand-text b { color: #ce93d8; }
        .nav-sidebar .nav-link { border-radius: 6px !important; margin: 2px 8px !important; color: rgba(255,255,255,0.75) !important; transition: all 0.2s; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: rgba(255,255,255,0.15) !important; color: #fff !important; }
        .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.5) !important; }
        .nav-sidebar .nav-link.active .nav-icon, .nav-sidebar .nav-link:hover .nav-icon { color: #ce93d8 !important; }
        .sidebar-heading { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.35); padding: 12px 20px 4px; display: block; }
        .user-panel { padding: 12px 16px !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .user-panel .info a { color: #fff !important; font-size: 14px; font-weight: 500; }
        .user-panel .info small { color: rgba(255,255,255,0.45); font-size: 11px; }
        .main-header { box-shadow: 0 1px 8px rgba(0,0,0,0.07) !important; border-bottom: 1px solid #e8eaf0 !important; }
        .content-wrapper { background: #f4f6f9 !important; }
        .content-header h1 { font-size: 20px; font-weight: 700; color: #4a148c; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .card-header { border-bottom: 1px solid #f0f2f5; padding: 14px 20px; background: #fff; }
        .stat-card { border-radius: 12px; padding: 22px 20px; color: #fff; position: relative; overflow: hidden; height: 100%; }
        .stat-card h3 { font-size: 34px; font-weight: 700; margin: 0 0 4px; }
        .stat-card p { font-size: 13px; margin: 0; opacity: 0.85; }
        .stat-card .stat-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 52px; opacity: 0.12; }
        .main-footer { background: #fff; border-top: 1px solid #e8eaf0; color: #aaa; font-size: 12px; }
        .avatar-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; flex-shrink: 0; }
        .badge-active   { background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-inactive { background:#ffebee; color:#b71c1c; border:1px solid #ef9a9a; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:600; }
        .table thead th { background:#f8f9fc; color:#4a148c; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; border-top:none; font-weight:600; padding:10px 16px; }
        .table td { vertical-align:middle; font-size:13px; padding:10px 16px; }
        .table-hover tbody tr:hover { background:#f8f9fc; }
        .alert-success-custom { background:#e8f5e9; color:#1b5e20; border:none; border-radius:8px; font-size:13px; padding:10px 16px; }
        .quick-action-btn { display:flex; align-items:center; gap:12px; padding:16px 20px; border-radius:10px; border:1.5px solid #e0e4ec; background:#fff; text-decoration:none; color:#333; transition:all 0.2s; margin-bottom:12px; }
        .quick-action-btn:hover { border-color:#4a148c; background:#f3e5f5; color:#4a148c; text-decoration:none; }
        .quick-action-btn .qa-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .quick-action-btn .qa-title { font-size:14px; font-weight:600; }
        .quick-action-btn .qa-sub { font-size:12px; color:#9e9e9e; margin-top:2px; }
        .pagination .page-link { color:#4a148c; border-radius:6px !important; margin:0 2px; font-size:13px; }
        .pagination .page-item.active .page-link { background:#4a148c; border-color:#4a148c; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- ===== NAVBAR ===== --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item mr-3">
                <span style="font-size:13px; color:#555;">
                    <i class="fas fa-circle text-success mr-1" style="font-size:8px;"></i>
                    {{ $counselee->full_name }}
                </span>
            </li>
            <li class="nav-item">
                <form action="{{ route('counselee.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="btn btn-sm btn-outline-danger"
                            style="border-radius:20px; font-size:12px; padding:5px 14px;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="main-sidebar elevation-0">
        <a href="{{ route('counselee.dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light"><b>P2P</b> Counselee</span>
        </a>
        <div class="sidebar">
            <div class="user-panel d-flex align-items-center">
                <div class="image">
                    <div class="avatar-circle" style="background:#6a1b9a; font-size:14px;">
                        {{ strtoupper(substr($counselee->first_name, 0, 1)) }}
                    </div>
                </div>
                <div class="info ml-2">
                    <a href="#" class="d-block">{{ $counselee->full_name }}</a>
                    <small style="text-transform:capitalize;">{{ $counselee->gender }} &middot; {{ $counselee->birthdate ? $counselee->birthdate->format('M d, Y') : '' }}</small>
                </div>
            </div>

            <nav class="mt-2 pb-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    <li class="nav-item">
                        <a href="{{ route('counselee.dashboard') }}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <span class="sidebar-heading">Appointments</span>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-plus"></i>
                            <p>Book Appointment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>My Appointments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Appointment History</p>
                        </a>
                    </li>

                    <span class="sidebar-heading">Account</span>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>My Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-lock"></i>
                            <p>Change Password</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    {{-- ===== CONTENT ===== --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right" style="background:transparent; padding:0; font-size:12px;">
                            <li class="breadcrumb-item"><a href="{{ route('counselee.dashboard') }}" style="color:#4a148c;">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                {{-- Flash Message --}}
                @if(session('message'))
                    <div class="alert-success-custom mb-3">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
                    </div>
                @endif

                {{-- Welcome Banner --}}
                <div class="card" style="background: linear-gradient(135deg, #4a148c, #6a1b9a); color:#fff; border-radius:12px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center flex-wrap" style="gap:16px;">
                            <div class="avatar-circle"
                                 style="background:rgba(255,255,255,0.2); width:60px; height:60px; font-size:24px; flex-shrink:0;">
                                {{ strtoupper(substr($counselee->first_name, 0, 1)) }}
                            </div>
                            <div style="flex:1;">
                                <h4 style="margin:0; font-weight:700;">Welcome back, {{ $counselee->first_name }}!</h4>
                                <p style="margin:5px 0 0; opacity:0.8; font-size:13px;">
                                    <i class="fas fa-envelope mr-1"></i> {{ $counselee->email }}
                                    &nbsp;&nbsp;
                                    <i class="fas fa-phone mr-1"></i> {{ $counselee->phone }}
                                </p>
                                <p style="margin:3px 0 0; opacity:0.7; font-size:12px;">
                                    <i class="fas fa-venus-mars mr-1"></i> {{ ucfirst($counselee->gender) }}
                                    &nbsp;&nbsp;
                                    <i class="fas fa-birthday-cake mr-1"></i>
                                    {{ $counselee->birthdate ? $counselee->birthdate->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                @if($counselee->status === 'active')
                                    <span class="badge-active">Active</span>
                                @else
                                    <span class="badge-inactive">{{ ucfirst($counselee->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stat Cards --}}
                <div class="row mb-3">
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="stat-card" style="background:linear-gradient(135deg,#4a148c,#6a1b9a);">
                            <h3>0</h3>
                            <p>Total Appointments</p>
                            <i class="fas fa-calendar-check stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="stat-card" style="background:linear-gradient(135deg,#e65100,#f57c00);">
                            <h3>0</h3>
                            <p>Pending</p>
                            <i class="fas fa-clock stat-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="stat-card" style="background:linear-gradient(135deg,#1565c0,#1976d2);">
                            <h3>0</h3>
                            <p>Completed</p>
                            <i class="fas fa-check-double stat-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Quick Actions --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <span style="color:#4a148c; font-size:14px; font-weight:600;">
                                    <i class="fas fa-bolt mr-2"></i> Quick Actions
                                </span>
                            </div>
                            <div class="card-body">
                                <a href="#" class="quick-action-btn">
                                    <div class="qa-icon" style="background:#f3e5f5; color:#4a148c;">
                                        <i class="fas fa-calendar-plus"></i>
                                    </div>
                                    <div>
                                        <div class="qa-title">Book Appointment</div>
                                        <div class="qa-sub">Schedule with a counselor</div>
                                    </div>
                                </a>
                                <a href="#" class="quick-action-btn">
                                    <div class="qa-icon" style="background:#e3f2fd; color:#1565c0;">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="qa-title">My Appointments</div>
                                        <div class="qa-sub">View all your appointments</div>
                                    </div>
                                </a>
                                <a href="#" class="quick-action-btn">
                                    <div class="qa-icon" style="background:#e8f5e9; color:#1b5e20;">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <div>
                                        <div class="qa-title">Update Profile</div>
                                        <div class="qa-sub">Edit your personal info</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Appointments --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <span style="color:#4a148c; font-size:14px; font-weight:600;">
                                    <i class="fas fa-calendar-check mr-2"></i> Recent Appointments
                                </span>
                                <a href="#" style="font-size:12px; color:#4a148c; text-decoration:none;">
                                    View All <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Counselor</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <i class="fas fa-calendar-times" style="font-size:40px; color:#e0e0e0; display:block; margin-bottom:10px;"></i>
                                                <p class="text-muted mb-1">No appointments yet.</p>
                                                <a href="#"
                                                   style="font-size:13px; color:#4a148c; font-weight:600;">
                                                    Book your first appointment
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
