<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — P2P Appointment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { --counselee-primary:#D30404; --counselee-accent:#4a148c; }

        /* Sidebar */
        .main-sidebar { background: linear-gradient(180deg, #2a0a4a 0%, #4a148c 100%) !important; }
        .brand-link { background: rgba(0,0,0,0.2) !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; padding: 12px 16px !important; height:64px; display:flex; align-items:center; }
        .brand-link img { max-height: 34px; }
        .sidebar-heading { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.35); padding: 14px 20px 4px; display: block; }
        .user-panel { padding: 12px 16px !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .user-panel .info a { color: #fff !important; font-size: 14px; font-weight: 600; }
        .user-panel .info small { color: rgba(255,255,255,0.45); font-size: 11px; }
        .nav-sidebar .nav-link { border-radius: 6px !important; margin: 2px 8px !important; padding: 9px 12px !important; color: rgba(255,255,255,0.75) !important; transition: all 0.2s !important; font-weight:600; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: rgba(255,255,255,0.15) !important; color: #fff !important; }
        .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.55) !important; }
        .nav-sidebar .nav-link.active .nav-icon, .nav-sidebar .nav-link:hover .nav-icon { color: #ff8a80 !important; }
        .nav-sidebar .nav-link.active { box-shadow: inset 3px 0 0 var(--counselee-primary); }

        /* Navbar */
        .main-header { box-shadow: 0 1px 8px rgba(0,0,0,0.07) !important; border-bottom: 1px solid #e8eaf0 !important; height:64px; }
        .main-header .navbar { padding: 0 16px; height: 64px; }

        /* Content */
        .content-wrapper { background: #f4f6f9 !important; }
        .content-header { padding: 18px 20px 0; }
        .content-header h1 { font-size: 20px; font-weight: 700; color: #1a1a2e; margin:0; }
        .breadcrumb { background: transparent; padding: 0; margin: 0; font-size: 12px; }
        .breadcrumb-item a { color: var(--counselee-primary); text-decoration:none; }
        .breadcrumb-item.active { color: #9e9e9e; }
        .breadcrumb-item + .breadcrumb-item::before { color: #ccc; }

        /* Generic */
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .avatar-circle { border-radius: 50%; display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; flex-shrink:0; }
        .alert { border:none; border-radius:8px; font-size:13px; padding:12px 16px; }
        .alert-success { background:#e8f5e9; color:#1b5e20; }
        .alert-danger  { background:#ffebee; color:#b71c1c; }

        /* Footer */
        .main-footer { background:#fff; border-top:1px solid #e8eaf0; color:#aaa; font-size:12px; padding:12px 20px; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

   
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block ml-2">
                <span style="font-size:13px; color:#9e9e9e;">@yield('page-title', 'Dashboard')</span>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto align-items-center">
            <li class="nav-item mr-3">
                <span style="font-size:13px; color:#555;">
                    <i class="fas fa-circle text-success mr-1" style="font-size:8px;"></i>
                    {{ Auth::guard('counselee')->user()->full_name }}
                </span>
            </li>
            <li class="nav-item">
                <form action="{{ route('counselee.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:20px; font-size:12px; padding:5px 14px;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

 
    <aside class="main-sidebar elevation-0">
        <a href="{{ route('counselee.dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light" style="color:#fff; font-size:16px; font-weight:700;">
                <b>P2P</b> Counselee
            </span>
        </a>
        <div class="sidebar">
            <div class="user-panel d-flex align-items-center">
                <div class="image">
                    <div class="avatar-circle" style="background:#fff; color:#4a148c; width:34px; height:34px; font-size:13px;">
                        {{ strtoupper(substr(Auth::guard('counselee')->user()->first_name, 0, 1)) }}
                    </div>
                </div>
                <div class="info ml-2">
                    <a href="{{ route('counselee.dashboard') }}" class="d-block">{{ Auth::guard('counselee')->user()->full_name }}</a>
                    <small>Counselee</small>
                </div>
            </div>

            <nav class="mt-2 pb-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <a href="{{ route('counselee.dashboard') }}"
                           class="nav-link {{ request()->routeIs('counselee.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <span class="sidebar-heading">Appointments</span>

                    <li class="nav-item">
                        <a href="{{ route('counselee.appointments.create') }}"
                           class="nav-link {{ request()->routeIs('counselee.appointments.create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-plus"></i>
                            <p>Book Appointment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('counselee.appointments.index') }}"
                           class="nav-link {{ request()->routeIs('counselee.appointments.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>My Appointments</p>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('page-title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('counselee.dashboard') }}"><i class="fas fa-home"></i> Home</a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                @if(session('success') || session('message'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') ?? session('message') }}
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
@stack('scripts')
</body>
</html>
