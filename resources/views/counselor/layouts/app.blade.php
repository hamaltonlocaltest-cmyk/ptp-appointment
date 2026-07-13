<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — P2P Appointment</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">

    <style>
        :root { --counselor-primary:#1b5e20; --counselor-accent:#2e7d32; }

        /* Sidebar */
        .main-sidebar { background: #0f5b5c;}
        .brand-link { background: #fff; border-bottom: 1px solid rgba(255,255,255,0.1) !important; padding: 14px 16px !important; height: 64px;}
        .sidebar-heading { font-size: 14px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.45); padding: 12px 20px 4px; display: block; border-top: 1px solid rgba(255,255,255,0.1);  margin-top: 10px; }
        .user-panel { padding: 12px 16px !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .user-panel .info a { color: #fff !important; font-size: 14px; font-weight: 600; }
        .user-panel .info small { color: rgba(255,255,255,0.75); font-size: 13px; }
        .nav-sidebar .nav-link { border-radius: 6px !important; margin: 2px 8px !important; padding: 9px 12px !important; color: rgba(255,255,255,0.75) !important; transition: all 0.2s !important; font-weight:600; }
        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active { background: rgba(255,255,255,0.5) !important; color: #fff !important; }
        .nav-sidebar .nav-link .nav-icon { color: rgba(255,255,255,0.55) !important; }
        .nav-sidebar .nav-link.active .nav-icon, .nav-sidebar .nav-link:hover .nav-icon { color: #a5d6a7 !important; }
        .nav-sidebar .nav-link.active { box-shadow: inset 3px 0 0 var(--counselor-primary); }
		
		.btn-outline-primary {color: #0f5b5c;border-color: #0f5b5c;}
		.btn-outline-primary:hover {color: #fff; background-color: #0f5b5c;  border-color: #0f5b5c;}

        /* Navbar */
        .main-header { box-shadow: 0 1px 8px rgba(0,0,0,0.07) !important; border-bottom: 1px solid #e8eaf0 !important; height:64px; }
        .main-header .navbar { padding: 0 16px; height: 64px; }

        /* Content */
        .content-wrapper { background: #f4f6f9 !important; }
        .content-header { padding: 18px 20px 0; }
        .content-header h1 { font-size: 20px; font-weight: 700; color: #1a1a2e; margin:0; }
        .breadcrumb { background: transparent; padding: 0; margin: 0; font-size: 12px; }
        .breadcrumb-item a { color: var(--counselor-primary); text-decoration:none; }
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
		
		/* Stat cards */
        .stat-card { border-radius: 12px; padding: 22px 20px; color: #fff; position: relative; overflow: hidden; height: 100%; }
        .stat-card h3 { font-size: 34px; font-weight: 700; margin: 0 0 4px; }
        .stat-card p { font-size: 13px; margin: 0; opacity: 0.85; }
        .stat-card .stat-icon { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 52px; opacity: 0.12; }
		.bg-admin {  background: #e6f4f4;  border: 1px solid #c5dede;  order-left: 2px solid #05a6a5;}.bg-counselor {  background: #eaf5ed;  border: 1px solid #abd8b4;  border-left: 2px solid #328944;}.bg-counselee{  background: #eef5fd;  border: 1px solid #d7ebf3;  border-left: 2px solid #068abd;}.bg-pending {  background: #fdf3ea;  border: 1px solid #ffd6b3;  border-left: 2px solid #f8790e;}.stat-card.bg-admin h3{color: #1f8582;}.stat-card.bg-admin p{color: #1f8582;}.stat-card.bg-admin .stat-icon{color: #1f8582;}.stat-card.bg-counselor h3{color: #328944;}.stat-card.bg-counselor p{color: #328944;}.stat-card.bg-counselor .stat-icon{color: #328944;}.stat-card.bg-counselee h3{color: #068abd;}.stat-card.bg-counselee p{color: #068abd;}.stat-card.bg-counselee .stat-icon{color: #068abd;}.stat-card.bg-pending h3{color: #f8790e;}.stat-card.bg-pending p{color: #f8790e;}.stat-card.bg-pending .stat-icon{color: #f8790e;}

        /* Badges */
        .badge-active   { background:#e8f5e9; color:#1b5e20; border:1px solid #a5d6a7; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }
        .badge-inactive { background:#ffebee; color:#b71c1c; border:1px solid #ef9a9a; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }
        .badge-pending  { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; white-space:nowrap; }

        /* Table */
        .table thead th { background:#f8f9fc; color:#1f8582; font-size:12px; text-transform:uppercase; letter-spacing:0.5px; border-top:none; font-weight:600; padding:10px 16px; }
        .table td { vertical-align:middle; font-size:13px; padding:10px 16px; color:#333; }
        .table-hover tbody tr:hover { background:#f8f9fc; }

        /* Action buttons */
        .btn-action { width:30px; height:30px; padding:0; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; border:none; cursor:pointer; transition:all 0.2s; }
        .btn-edit          { background:#e3f2fd; color:#1565c0; }
        .btn-edit:hover    { background:#1565c0; color:#fff; }
        .btn-toggle-active { background:#ffebee; color:#c62828; }
        .btn-toggle-active:hover { background:#c62828; color:#fff; }
        .btn-toggle-inactive { background:#e8f5e9; color:#1b5e20; }
        .btn-toggle-inactive:hover { background:#1b5e20; color:#fff; }
        .btn-delete        { background:#ffebee; color:#c62828; }
        .btn-delete:hover  { background:#c62828; color:#fff; }

        /* Forms */
        .form-label { font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:5px; display:block; }
        .form-control { border-radius:7px !important; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px; height:auto; transition:border-color 0.2s; }
        .form-control:focus { border-color:#1f8582; box-shadow:0 0 0 3px rgba(26,35,126,0.1); }
        select.form-control { padding:8px 12px; }

        /* Search */
        .search-box { position:relative; }
        .search-box .form-control { padding-left:36px; border-radius:20px !important; background:#f8f9fc; }
        .search-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9e9e9e; font-size:13px; z-index:1; }

        /* Alerts */
        .alert { border:none; border-radius:8px; font-size:13px; padding:10px 16px; }
        .alert-success { background:#e8f5e9; color:#1b5e20; }
        .alert-danger  { background:#ffebee; color:#b71c1c; }

        /* Footer */
        .main-footer { background:#fff; border-top:1px solid #e8eaf0; color:#aaa; font-size:12px; padding:12px 20px; }

        /* Avatar */
        .avatar-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; color:#fff; flex-shrink:0; }

        /* Pagination */
        .pagination { margin:0; }
        .page-link { color:#1f8582; border-radius:6px !important; margin:0 2px; border:1px solid #e0e4ec; font-size:13px; }
        .page-item.active .page-link { background:#1f8582; border-color:#1f8582; }

        /* DataTables */
.dataTables_wrapper .dataTables_filter input {
    border: 1.5px solid #e0e4ec !important;
    border-radius: 20px !important;
    padding: 5px 14px !important;
    font-size: 13px !important;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #1f8582 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(26,35,126,0.1) !important;
}
.dataTables_wrapper .dataTables_length select {
    border: 1.5px solid #e0e4ec !important;
    border-radius: 7px !important;
    padding: 4px 8px !important;
    font-size: 13px !important;
}
.dataTables_wrapper .dataTables_info {
    font-size: 12px !important;
    color: #9e9e9e !important;
    padding-top: 10px !important;
}
.dataTables_wrapper .dataTables_paginate {
    padding-top: 8px !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 6px !important;
    font-size: 13px !important;
    padding: 4px 10px !important;
    margin: 0 2px !important;
    border: 1px solid #e0e4ec !important;
    color: #1f8582 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #1f8582 !important;
    border-color: #1f8582 !important;
    color: #fff !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e8eaf6 !important;
    border-color: #1f8582 !important;
    color: #1f8582 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #ccc !important;
    border-color: #e0e4ec !important;
}

.btn-primary {
    color: #fff;
    background-color: #1f8582;
    border-color: #1f8582;
}

.btn-primary:hover {
    color: #fff;
    background-color: #186c69;
    border-color: #145c59;
}

.btn-primary:focus,
.btn-primary.focus {
    color: #fff;
    background-color: #1f8582;
    border-color: #186c69;
    box-shadow: 0 0 0 0.2rem rgba(31, 133, 130, 0.25);
}

.btn-primary:active,
.btn-primary.active {
    color: #fff;
    background-color: #145c59;
    border-color: #104b49;
}


.edit-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:16px 22px;
    margin-bottom:20px;
    background:linear-gradient(135deg,#eef8f7,#d8f1ef);
    border:1px solid #bfe4e1;
    border-left:5px solid #1f8582;
    border-radius:12px;
}

.edit-icon{
    width:52px;
    height:52px;
    border-radius:12px;
    background:#1f8582;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    box-shadow:0 8px 20px rgba(31,133,130,.25);
}

.edit-title{
    font-size:13px;
    color:#666;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.edit-subtitle{
    font-size:20px;
    font-weight:700;
    color:#1f8582;
    margin-top:2px;
}

.status-badge{
    display:inline-flex;
    align-items:center;
    padding:8px 16px;
    border-radius:30px;
    background:#e9f8ef;
    color:#198754;
    font-size:13px;
    font-weight:600;
    border:1px solid #c9ead4;
}

.stat-card.bg-inactive {
  background: #ffe9e9;
  border: 1px solid #ffc2c2;
  border-left: 2px solid #dc2426;
}

.stat-card.bg-inactive h3, .stat-card.bg-inactive p{color:#dc2426}

.stat-card.bg-active {
  background: #eaf5ed;
  border: 1px solid #abd8b4;
  border-left: 2px solid #328944;
}

.stat-card.bg-active h3 {
  color: #328944;
}

.stat-card.bg-active p {
  color: #328944;
}

.stat-card.bg-pending {
 background: #fff0e4;
  border: 1px solid #ffdec4;
  border-left: 2px solid #ea5b00;
}

.stat-card.bg-pending h3 {
  color: #ea5b00;
}

.stat-card.bg-pending p {
  color: #ea5b00;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{padding: 0 !important; border: 0 solid #e0e4ec !important;}


.recent-header {
	background: #fff;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;
	padding: 15px 20px;
}

.recent-title {
	font-weight: 600;
	display: flex;
	align-items: center;
}

.recent-title i {
	color: #1b5e20;
}

.view-all-link {
	font-size: 12px;
	color: #3949ab;
	text-decoration: none;
	white-space: nowrap;
	font-weight: 500;
}

.view-all-link:hover {
	text-decoration: none;
}

.recent-title {
	flex: 0 0 60%;
}

.view-all-link {
	flex: 0 0 40%;
	text-align: end;
}

@media (max-width:767px) {
	.recent-header {
		/*flex-direction:column;						align-items:flex-start;*/
	}

	.view-all-link {
		width: 100%;
		text-align: right;
		margin-top: 5px;
	}
}


		
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
                    {{ Auth::guard('counselor')->user()->full_name }}
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

    <aside class="main-sidebar elevation-0">
		<a href="{{ route('counselor.dashboard') }}" class="brand-link">
            <img src="{{ asset('images/persontoperson-logo.png') }}" class="img-fluid" alt="Person to Person Appointment">
        </a>
        <div class="sidebar">
            <div class="user-panel d-flex align-items-center">
                <div class="image">
                    <div class="avatar-circle" style="background:#fff; color:#1b5e20; width:34px; height:34px; font-size:13px;">
                        {{ strtoupper(substr(Auth::guard('counselor')->user()->first_name, 0, 1)) }}
                    </div>
                </div>
                <div class="info ml-2">
                    <a href="{{ route('counselor.dashboard') }}" class="d-block">{{ Auth::guard('counselor')->user()->full_name }}</a>
                    <small>{{ Auth::guard('counselor')->user()->specialization }}</small>
                </div>
            </div>

            <nav class="mt-2 pb-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <a href="{{ route('counselor.dashboard') }}"
                           class="nav-link {{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <span class="sidebar-heading">Appointments</span>

                    <li class="nav-item">
                        <a href="{{ route('counselor.appointments.index') }}"
                           class="nav-link {{ request()->routeIs('counselor.appointments.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>My Appointments</p>
                        </a>
                    </li>

                    <span class="sidebar-heading">Feedback &amp; Complaints</span>

                    <li class="nav-item">
                        <a href="{{ route('counselor.feedback.index') }}"
                           class="nav-link {{ request()->routeIs('counselor.feedback.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-star"></i>
                            <p>Feedback</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('counselor.complaints.index') }}"
                           class="nav-link {{ request()->routeIs('counselor.complaints.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-circle"></i>
                            <p>Complaints</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('counselee.donations.create') }}"
                           class="nav-link {{ request()->routeIs('counselee.donations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hand-holding-heart"></i>
                            <p>Donate</p>
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
                                <a href="{{ route('counselor.dashboard') }}"><i class="fas fa-home"></i> Home</a>
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

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- Responsive -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.1/js/responsive.bootstrap4.min.js"></script>

@stack('scripts')
</body>
</html>
