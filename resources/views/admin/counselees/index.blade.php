@extends('admin.layouts.app')
@section('title', 'Counselees')
@section('page-title', 'Counselees')
@section('breadcrumb')
    <li class="breadcrumb-item active">Counselees</li>
@endsection

@section('content')

<style>
	.stat-card.bg-inactive{background: #ffe9e9; border: 1px solid #ffc2c2; border-left: 2px solid #dc2426;}
	.stat-card.bg-inactive h3 {color: #dc2426;}
	.stat-card.bg-inactive p{color: #dc2426;}
</style>

<div class="row mb-3">
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card bg-counselee">
            <h3>{{ $counts['total'] }}</h3><p>Total Counselees</p>
            <i class="fas fa-users stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card bg-counselor">
            <h3>{{ $counts['active'] }}</h3><p>Active</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card bg-inactive">
            <h3>{{ $counts['inactive'] }}</h3><p>Inactive</p>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-users mr-2"></i> Counselee List
        </span>
        <a href="{{ route('admin.counselees.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Add Counselee
        </a>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="counseleesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Counselee</th>
                        <th>Contact</th>
                        <th>Gender</th>
                        <th>Birthdate</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th class="text-center" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($counselees as $i => $c)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle mr-3" style="background:#{{ substr(md5($c->email),0,6) }};">
                                {{ strtoupper(substr($c->first_name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600; color:#1a1a2e;">{{ $c->full_name }}</div>
                                <div style="font-size:12px; color:#9e9e9e;">{{ $c->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $c->telephone1 ?? $c->phone ?? '—' }}</td>
                    <td>
                        <span style="background:#f3e5f5; color:#4a148c; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; text-transform:capitalize;">
                            {{ $c->gender }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:#555;">
                        {{ $c->birthdate ? $c->birthdate->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        @if($c->status === 'active')
                            <span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Active</span>
                        @else
                            <span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Inactive</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#aaa;">{{ $c->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            <a href="{{ route('admin.counselees.show', $c) }}"
                               class="btn-action" title="View"
                               style="background:#e3f2fd; color:#1565c0;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.counselees.edit', $c) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.counselees.toggle', $c) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn-action {{ $c->status==='active' ? 'btn-toggle-active' : 'btn-toggle-inactive' }}"
                                    title="{{ $c->status==='active' ? 'Set Inactive' : 'Set Active' }}">
                                    <i class="fas {{ $c->status==='active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.counselees.destroy', $c) }}" method="POST"
                                  onsubmit="return confirmDelete('{{ $c->full_name }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-users" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-2">No counselees found.</p>
                        <a href="{{ route('admin.counselees.create') }}"
                           class="btn btn-sm" style="background:#4a148c; color:#fff; border-radius:7px; padding:7px 18px;">
                            Add First Counselee
                        </a>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#counseleesTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: '',
            searchPlaceholder: 'Search counselees...',
            info: 'Showing _START_ to _END_ of _TOTAL_ counselees',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last:  '<i class="fas fa-angle-double-right"></i>',
                next:  '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
            }
        },
        columnDefs: [{ orderable: false, targets: [-1] }]
    });
});
function confirmDelete(name) {
    return confirm('Are you sure you want to delete "' + name + '"?\nThis action cannot be undone.');
}
</script>
@endpush