@extends('admin.layouts.app')
@section('title', 'Countries')
@section('page-title', 'Countries')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item active">Countries</li>
@endsection

@section('content')

<div class="row mb-3">
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
            <h3>{{ $counts['total'] }}</h3><p>Total Countries</p>
            <i class="fas fa-globe stat-icon"></i>
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
        <span style="font-weight:600; flex:auto"><i class="fas fa-globe mr-2"></i> Country List</span>
        <a href="{{ route('admin.masters.countries.create') }}"
           class="btn btn-sm" style="background:#1a237e; color:#fff; border-radius:7px; padding:7px 18px; font-size:13px; font-weight:600;">
            <i class="fas fa-plus mr-1"></i> Add Country
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="countriesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Phone Code</th>
                        <th>Status</th>
                        <th class="text-center" width="110">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($countries as $i => $c)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $c->name }}</td>
                    <td><code style="background:#f4f6f9; padding:2px 8px; border-radius:4px;">{{ $c->code }}</code></td>
                    <td>{{ $c->phone_code ? '+' . $c->phone_code : '—' }}</td>
                    <td>
                        @if($c->status === 'active')
                            <span class="badge-active">Active</span>
                        @else
                            <span class="badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            <a href="{{ route('admin.masters.countries.edit', $c) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.masters.countries.toggle', $c) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn-action {{ $c->status==='active' ? 'btn-toggle-active' : 'btn-toggle-inactive' }}"
                                    title="{{ $c->status==='active' ? 'Set Inactive' : 'Set Active' }}">
                                    <i class="fas {{ $c->status==='active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
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
    $('#countriesTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [25, 50, 100, 250],
        language: { search: '', searchPlaceholder: 'Search countries...', info: 'Showing _START_ to _END_ of _TOTAL_ countries', emptyTable: '<p class="text-muted mb-0 text-center py-5">No countries found.</p>' },
        columnDefs: [{ orderable: false, targets: [-1] }]
    });
});
</script>
@endpush
