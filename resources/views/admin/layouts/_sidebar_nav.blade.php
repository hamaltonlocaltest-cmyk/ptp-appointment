{{--
=====================================================================
REPLACE your sidebar nav <ul> inside resources/views/admin/layouts/app.blade.php
with this updated version that includes the Masters section
=====================================================================
--}}

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    {{-- Dashboard --}}
    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
        </a>
    </li>

    {{-- ======================== --}}
    {{-- USER MANAGEMENT          --}}
    {{-- ======================== --}}
    <span class="sidebar-heading">User Management</span>

    {{-- Counselors --}}
    <li class="nav-item {{ request()->routeIs('admin.counselors.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.counselors.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-md"></i>
            <p>Counselors <i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.counselors.index') }}"
                   class="nav-link {{ request()->routeIs('admin.counselors.index') && !request('status') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list"></i>
                    <p>All Counselors</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.counselors.create') }}"
                   class="nav-link {{ request()->routeIs('admin.counselors.create') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>Add Counselor</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.counselors.index', ['status' => 'pending']) }}"
                   class="nav-link {{ request('status')=='pending' && request()->routeIs('admin.counselors.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>Pending Approval</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- Counselees --}}
    <li class="nav-item {{ request()->routeIs('admin.counselees.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.counselees.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Counselees <i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.counselees.index') }}"
                   class="nav-link {{ request()->routeIs('admin.counselees.index') && !request('status') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list"></i>
                    <p>All Counselees</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.counselees.create') }}"
                   class="nav-link {{ request()->routeIs('admin.counselees.create') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>Add Counselee</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.counselees.index', ['status' => 'active']) }}"
                   class="nav-link {{ request('status')=='active' && request()->routeIs('admin.counselees.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-circle"></i>
                    <p>Active Counselees</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- ======================== --}}
    {{-- MASTERS                  --}}
    {{-- ======================== --}}
    <span class="sidebar-heading">Masters</span>

    {{-- Counsel Types --}}
    <li class="nav-item {{ request()->routeIs('admin.masters.counsel-types.*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ request()->routeIs('admin.masters.counsel-types.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-comments"></i>
            <p>Counsel Types <i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.masters.counsel-types.index') }}"
                   class="nav-link {{ request()->routeIs('admin.masters.counsel-types.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-list"></i>
                    <p>All Types</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.masters.counsel-types.create') }}"
                   class="nav-link {{ request()->routeIs('admin.masters.counsel-types.create') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>Add Type</p>
                </a>
            </li>
        </ul>
    </li>

    {{-- ======================== --}}
    {{-- SYSTEM                   --}}
    {{-- ======================== --}}
    <span class="sidebar-heading">System</span>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>Reports</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>Settings</p>
        </a>
    </li>

</ul>
