@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')


<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-admin">
            <h3>{{ $total_admins }}</h3>
            <p>Total Admins</p>
            <i class="fas fa-user-shield stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselor">
            <h3>{{ $total_counselors }}</h3>
            <p>Total Counselors</p>
            <i class="fas fa-user-md stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselee">
            <h3>{{ $total_counselees }}</h3>
            <p>Total Counselees</p>
            <i class="fas fa-users stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-pending">
            <h3>{{ $pending_counselors }}</h3>
            <p>Pending Counselors</p>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <span style="font-size:13px; font-weight:600; color:#1a237e; margin-right:16px;">
                    <i class="fas fa-bolt mr-1"></i> Quick Actions
                </span>
                <a href="{{ route('admin.counselors.create') }}" class="btn btn-sm mr-2"
                   style="background:#e8f5e9; color:#1b5e20; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #c8e6c9;">
                    <i class="fas fa-plus mr-1"></i> Add Counselor
                </a>
                <a href="{{ route('admin.counselees.create') }}" class="btn btn-sm mr-2"
                   style="background:#f3e5f5; color:#4a148c; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #e1bee7;">
                    <i class="fas fa-plus mr-1"></i> Add Counselee
                </a>
                <a href="{{ route('admin.counselors.index', ['status' => 'pending']) }}" class="btn btn-sm"
                   style="background:#fff3e0; color:#e65100; border-radius:20px; padding:6px 16px; font-size:12px; font-weight:600; border:1px solid #ffe0b2;">
                    <i class="fas fa-clock mr-1"></i> View Pending Approvals
                </a>
            </div>
        </div>
    </div>
</div>


<div class="row">
   
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                <span style="color:#1a237e; font-weight:600; font-size:14px;">
                    <i class="fas fa-user-md mr-2" style="color:#1b5e20;"></i> Recent Counselors
                </span>
                <a href="{{ route('admin.counselors.index') }}"
                   style="font-size:12px; color:#3949ab; text-decoration:none;">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_counselors as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle mr-2"
                                         style="background:#{{ substr(md5($c->email), 0, 6) }}; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; font-weight:700; flex-shrink:0;">
                                        {{ strtoupper(substr($c->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600; font-size:13px; color:#222;">{{ $c->full_name }}</div>
                                        <div style="font-size:11px; color:#999;">{{ $c->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;">{{ $c->specialization }}</td>
                            <td>
                                @if($c->status === 'active')
                                    <span class="badge-active">Active</span>
                                @elseif($c->status === 'inactive')
                                    <span class="badge-inactive">Inactive</span>
                                @else
                                    <span class="badge-pending">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="fas fa-user-md" style="font-size:28px; color:#ddd; display:block; margin-bottom:8px;"></i>
                                No counselors yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                <span style="color:#1a237e; font-weight:600; font-size:14px;">
                    <i class="fas fa-users mr-2" style="color:#4a148c;"></i> Recent Counselees
                </span>
                <a href="{{ route('admin.counselees.index') }}"
                   style="font-size:12px; color:#3949ab; text-decoration:none;">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_counselees as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle mr-2"
                                         style="background:#{{ substr(md5($c->email), 0, 6) }}; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:12px; font-weight:700; flex-shrink:0;">
                                        {{ strtoupper(substr($c->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600; font-size:13px; color:#222;">{{ $c->full_name }}</div>
                                        <div style="font-size:11px; color:#999;">{{ $c->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px; text-transform:capitalize;">{{ $c->gender }}</td>
                            <td>
                                @if($c->status === 'active')
                                    <span class="badge-active">Active</span>
                                @else
                                    <span class="badge-inactive">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="fas fa-users" style="font-size:28px; color:#ddd; display:block; margin-bottom:8px;"></i>
                                No counselees yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection