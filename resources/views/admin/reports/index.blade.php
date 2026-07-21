@extends('admin.layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')
@section('breadcrumb')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')

<div class="row">
    @php
    $reports = [
        ['route' => 'admin.reports.appointments-summary', 'icon' => 'fas fa-calendar-check', 'color' => '#1a237e', 'title' => 'Appointments Summary', 'desc' => 'Counts and list of appointments by status, mode, and counselling area over time.'],
        ['route' => 'admin.reports.overdue-appointments', 'icon' => 'fas fa-hourglass-half', 'color' => '#c62828', 'title' => 'Overdue Appointments', 'desc' => 'Sessions whose scheduled time has passed but were never marked completed.'],
        ['route' => 'admin.reports.cancellations', 'icon' => 'fas fa-calendar-times', 'color' => '#b71c1c', 'title' => 'Cancellation Report', 'desc' => 'Who cancels and why — spot problem patterns by counselor or counsellee.'],
        ['route' => 'admin.reports.counselor-performance', 'icon' => 'fas fa-user-tie', 'color' => '#1b5e20', 'title' => 'Counselor Performance', 'desc' => 'Session load, completion rate, and average rating per counselor.'],
        ['route' => 'admin.reports.counselor-utilization', 'icon' => 'fas fa-business-time', 'color' => '#2e7d32', 'title' => 'Counselor Utilization', 'desc' => 'Booked hours vs. available hours — spot under- or over-booked counselors.'],
        ['route' => 'admin.reports.feedback-ratings', 'icon' => 'fas fa-star', 'color' => '#f9a825', 'title' => 'Feedback & Ratings', 'desc' => 'Rating distribution and a low-rating flag list for follow-up.'],
        ['route' => 'admin.reports.complaints', 'icon' => 'fas fa-exclamation-circle', 'color' => '#d32f2f', 'title' => 'Complaints Report', 'desc' => 'Status, resolution time, and grouping by counselor or filer.'],
        ['route' => 'admin.reports.counselling-demand', 'icon' => 'fas fa-chart-pie', 'color' => '#6a1b9a', 'title' => 'Counselling Area Demand', 'desc' => 'Most requested areas vs. counselor coverage — spot supply/demand gaps.'],
        ['route' => 'admin.reports.city-coverage-gap', 'icon' => 'fas fa-map-marked-alt', 'color' => '#e65100', 'title' => 'City Coverage Gap', 'desc' => 'Cities with counselees but no local in-person counselor.'],
        ['route' => 'admin.reports.registrations', 'icon' => 'fas fa-user-plus', 'color' => '#00695c', 'title' => 'Registration & Growth', 'desc' => 'New counselor/counselee signups over time, by status and location.'],
        ['route' => 'admin.reports.donations', 'icon' => 'fas fa-hand-holding-heart', 'color' => '#ad1457', 'title' => 'Donations Report', 'desc' => 'Revenue totals, status breakdown, and donation trend.'],
        ['route' => 'admin.reports.leave-calendar', 'icon' => 'fas fa-calendar-minus', 'color' => '#37474f', 'title' => 'Counselor Leave Calendar', 'desc' => "Who's unavailable and when, across all counselors."],
    ];
    @endphp

    @foreach($reports as $r)
    <div class="col-lg-4 col-md-6 mb-4">
        <a href="{{ route($r['route']) }}" class="report-card d-block">
            <div class="report-icon" style="background:{{ $r['color'] }}1a; color:{{ $r['color'] }};">
                <i class="{{ $r['icon'] }}"></i>
            </div>
            <div class="report-title">{{ $r['title'] }}</div>
            <div class="report-desc">{{ $r['desc'] }}</div>
            <div class="report-cta">
                Open Report <i class="fas fa-arrow-right ml-1"></i>
            </div>
        </a>
    </div>
    @endforeach
</div>

<style>
.report-card {
    background:#fff; border-radius:14px; border:1px solid #e0e4ec; padding:22px;
    height:100%; text-decoration:none; display:flex; flex-direction:column;
    transition:.2s; box-shadow:0 2px 8px rgba(0,0,0,.03);
}
.report-card:hover { box-shadow:0 8px 24px rgba(0,0,0,.09); transform:translateY(-2px); text-decoration:none; }
.report-icon {
    width:48px; height:48px; border-radius:12px; display:flex; align-items:center;
    justify-content:center; font-size:20px; margin-bottom:14px;
}
.report-title { font-size:15.5px; font-weight:700; color:#1a1a2e; margin-bottom:6px; }
.report-desc { font-size:12.5px; color:#8a8a95; line-height:1.6; flex:1; }
.report-cta { font-size:12px; font-weight:700; color:#1a237e; margin-top:14px; }
</style>

@endsection
