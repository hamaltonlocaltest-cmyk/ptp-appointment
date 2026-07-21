@extends('admin.layouts.app')
@section('title', 'Cities')
@section('page-title', 'Cities')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item active">Cities</li>
@endsection

@section('content')

<div class="row mb-3">
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
            <h3>{{ $counts['total'] }}</h3><p>Total Cities</p>
            <i class="fas fa-city stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
            <h3>{{ $counts['active'] }}</h3><p>Active</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#b71c1c,#c62828);">
            <h3>{{ $counts['inactive'] }}</h3><p>Inactive</p>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto"><i class="fas fa-city mr-2"></i> City List</span>
        <a href="{{ route('admin.masters.cities.create') }}"
           class="btn btn-sm" style="background:#1a237e; color:#fff; border-radius:7px; padding:7px 18px; font-size:13px; font-weight:600;">
            <i class="fas fa-plus mr-1"></i> Add City
        </a>
    </div>
    <div class="card-body">

        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="state_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All States</option>
                        @foreach($states as $s)
                        <option value="{{ $s->id }}" {{ request('state_id') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->country->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="citiesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Name</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th class="text-center" width="110">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($cities as $i => $city)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $city->name }}</td>
                    <td>{{ $city->state->name }}</td>
                    <td>{{ $city->country->name }}</td>
                    <td>
                        @if($city->status === 'active')
                            <span class="badge-active">Active</span>
                        @else
                            <span class="badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            <a href="{{ route('admin.masters.cities.edit', $city) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.masters.cities.toggle', $city) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn-action {{ $city->status==='active' ? 'btn-toggle-active' : 'btn-toggle-inactive' }}"
                                    title="{{ $city->status==='active' ? 'Set Inactive' : 'Set Active' }}">
                                    <i class="fas {{ $city->status==='active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#citiesTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [25, 50, 100],
        language: { search: '', searchPlaceholder: 'Search cities...', info: 'Showing _START_ to _END_ of _TOTAL_ cities', emptyTable: '<p class="text-muted mb-0 text-center py-5">No cities found.</p>' },
        columnDefs: [{ orderable: false, targets: [-1] }]
    });
});
</script>
@endpush
