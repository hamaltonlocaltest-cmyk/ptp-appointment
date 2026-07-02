<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — P2P Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <style>
        :root {
            --primary: #1a237e;
            --primary-light: #3949ab;
            --primary-dark: #0d1642;
            --success: #1b5e20;
            --danger: #b71c1c;
        }
        .main-sidebar { background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary) 100%) !important; }
        .brand-link { background: rgba(0,0,0,0.2) !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; padding: 14px 16px !important; }
        .brand-text { font-size: 16px !important; font-weight: 700 !important; letter-spacing: 0.5px; }
        .brand-text b { color: #90caf9; }
        .nav-sidebar .nav-link { border-radius: 6px !important; margin: 2px 8px !important; padding: 10px 12px !important; color: rgba(255,255,255,0.75) !important; transition: all 0.2s !important; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: rgba(255,255,255,0.15) !important; color: #fff !important; }
        .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.6) !important; }
        .nav-sidebar .nav-link.active .nav-icon, .nav-sidebar .nav-link:hover .nav-icon { color: #90caf9 !important; }
        .sidebar-heading { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.35); padding: 12px 20px 4px; }
        .user-panel { padding: 12px 16px !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .user-panel .info a { color: #fff !important; font-weight: 500; }
        .user-panel .info small { color: rgba(255,255,255,0.5); font-size: 11px; }
        .main-header { box-shadow: 0 1px 8px rgba(0,0,0,0.08) !important; border-bottom: 1px solid #e8eaf0; }
        .content-wrapper { background: #f4f6f9 !important; }
        .content-header h1 { font-size: 20px; font-weight: 700; color: #1a237e; }
        .breadcrumb-item a { color: #3949ab; }
        /* Cards */
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .card-header { border-radius: 10px 10px 0 0 !important; padding: 14px 20px; font-weight: 600; font-size: 14px; }
        /* Stat boxes */
        .stat-card { border-radius: 12px; padding: 20px; color: #fff; position: relative; overflow: hidden; }
        .stat-card .stat-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 48px; opacity: 0.15; }
        .stat-card h3 { font-size: 32px; font-weight: 700; margin: 0; }
        .stat-card p { font-size: 13px; margin: 4px 0 0; opacity: 0.85; }
        .stat-card .stat-footer { margin-top: 12px; font-size: 12px; opacity: 0.75; }
        .bg-admin { background: linear-gradient(135deg, #1a237e, #3949ab); }
        .bg-counselor { background: linear-gradient(135deg, #1b5e20, #2e7d32); }
        .bg-counselee { background: linear-gradient(135deg, #4a148c, #6a1b9a); }
        .bg-pending { background: linear-gradient(135deg, #e65100, #f57c00); }
        /* Badges */
        .badge-active { background: #e8f5e9; color: #1b5e20; border: 1px solid #a5d6a7; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-inactive { background: #ffebee; color: #b71c1c; border: 1px solid #ef9a9a; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-pending { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        /* Table */
        .table thead th { background: #f8f9fc; color: #1a237e; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-top: none; font-weight: 600; }
        .table td { vertical-align: middle; font-size: 13px; }
        .avatar-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: #fff; flex-shrink: 0; }
        /* Buttons */
        .btn-action { width: 30px; height: 30px; padding: 0; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; border: none; transition: all 0.2s; }
        .btn-edit { background: #e3f2fd; color: #1565c0; }
        .btn-edit:hover { background: #1565c0; color: #fff; }
        .btn-toggle-active { background: #e8f5e9; color: #1b5e20; }
        .btn-toggle-active:hover { background: #1b5e20; color: #fff; }
        .btn-toggle-inactive { background: #ffebee; color: #c62828; }
        .btn-toggle-inactive:hover { background: #c62828; color: #fff; }
        .btn-delete { background: #ffebee; color: #c62828; }
        .btn-delete:hover { background: #c62828; color: #fff; }
        /* Form */
        .form-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 5px; }
        .form-control { border-radius: 7px; border: 1px solid #dde3ec; font-size: 13px; padding: 8px 12px; }
        .form-control:focus { border-color: #3949ab; box-shadow: 0 0 0 3px rgba(57,73,171,0.12); }
        /* Alerts */
        .alert { border: none; border-radius: 8px; font-size: 13px; }
        .alert-success { background: #e8f5e9; color: #1b5e20; }
        .alert-danger { background: #ffebee; color: #b71c1c; }
        /* Modal */
        .modal-content { border: none; border-radius: 12px; }
        .modal-header { border-bottom: 1px solid #f0f0f0; padding: 16px 20px; }
        .modal-footer { border-top: 1px solid #f0f0f0; padding: 12px 20px; }
        /* Search */
        .search-box { position: relative; }
        .search-box input { padding-left: 36px; border-radius: 20px; background: #f8f9fc; border: 1px solid #e8eaf0; }
        .search-box .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9e9e9e; font-size: 13px; }
        .main-footer { background: #fff; border-top: 1px solid #e8eaf0; color: #9e9e9e; font-size: 12px; }
    </style>
    @stack('styles')
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
            <li class="nav-item mr-2">
                <span class="text-muted" style="font-size:13px;">
                    <i class="fas fa-circle text-success" style="font-size:8px;"></i>
                    &nbsp;{{ Auth::user()->name }}
                </span>
            </li>
            <li class="nav-item">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar elevation-1">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <span class="brand-text"><b>P2P</b> Appointment</span>
        </a>
        <div class="sidebar">
            <div class="user-panel d-flex">
                <div class="image">
                    <div class="avatar-circle bg-primary" style="background:#3949ab;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="info ml-2">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                    <small>Super Admin</small>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="sidebar-heading">User Management</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.counselors.index') }}" class="nav-link {{ request()->routeIs('admin.counselors.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Counselors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.counselees.index') }}" class="nav-link {{ request()->routeIs('admin.counselees.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Counselees</p>
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
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                @yield('content')
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
@stack('scripts')
</body>
</html>