@extends('admin.layouts.app')
@section('title', 'Counselees')
@section('page-title', 'Counselees')
@section('breadcrumb')
    <li class="breadcrumb-item active">Counselees</li>
@endsection

@section('content')


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

        <div class="">
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
                            <div class="avatar-circle mr-3">
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
                        <span style="background:#eaf7f5; color:#087a7f; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; text-transform:capitalize;">
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
                               style="background:#eaf7f5; color:#087a7f;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.counselees.edit', $c) }}"
                               class="btn-action btn-edit" title="Edit" style="background:#eaf7f5; color:#087a7f;">
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
                            <form action="{{ route('admin.counselees.destroy', $c) }}" method="POST" id="delete-form-{{ $c->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action btn-delete" title="Delete"
                                        onclick="openDeleteModal('{{ $c->id }}', '{{ $c->full_name }}')">
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

<div class="modal fade" id="deleteCounseleeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-trash-alt" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Delete Counselee?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    You're about to permanently remove
                    <strong id="deleteCounseleeName" style="color:#1a1a2e;"></strong>.
                    This action cannot be undone and will also remove their appointment history.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteCounseleeBtn" class="btn flex-fill"
                        style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-trash mr-1"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#counseleesTable').DataTable({
        responsive: true,
		autoWidth:false,
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

let pendingDeleteFormId = null;

function openDeleteModal(id, name) {
    pendingDeleteFormId = 'delete-form-' + id;
    document.getElementById('deleteCounseleeName').textContent = name;
    $('#deleteCounseleeModal').modal('show');
}

document.getElementById('confirmDeleteCounseleeBtn').addEventListener('click', function () {
    if (pendingDeleteFormId) {
        document.getElementById(pendingDeleteFormId).submit();
    }
});
</script>
@endpush