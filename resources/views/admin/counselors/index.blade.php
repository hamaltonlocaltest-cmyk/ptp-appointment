@extends('admin.layouts.app')
@section('title', 'Counselors')
@section('page-title', 'Counselors')
@section('breadcrumb')
    <li class="breadcrumb-item active">Counselors</li>
@endsection

@section('content')


<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselee" >
            <h3>{{ $counts['total'] }}</h3><p>Total Counselors</p>
            <i class="fas fa-user-md stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-active" >
            <h3>{{ $counts['active'] }}</h3><p>Active</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-inactive" >
            <h3>{{ $counts['inactive'] }}</h3><p>Inactive</p>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-pending" >
            <h3>{{ $counts['pending'] }}</h3><p>Pending</p>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-user-md mr-2" ></i> Counselor List
        </span>
        <a href="{{ route('admin.counselors.create') }}"
           class="btn btn-primary btn-sm" >
            <i class="fas fa-plus mr-1"></i> Add Counselor
        </a>
    </div>
    <div class="card-body">

        
      <!--  <form method="GET" action="{{ route('admin.counselors.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-5 mb-2">
                    <label class="form-label">Search</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control"
                               placeholder="Name, email, specialization..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status')=='active'   ?'selected':'' }}>Active</option>
                        <option value="inactive" {{ request('status')=='inactive' ?'selected':'' }}>Inactive</option>
                        <option value="pending"  {{ request('status')=='pending'  ?'selected':'' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2 d-flex" style="gap:8px;">
                    <button type="submit" class="btn btn-sm" style="background:#1a237e; color:#fff; border-radius:7px; padding:8px 18px; font-size:13px;">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.counselors.index') }}" class="btn btn-sm btn-light" style="border-radius:7px; padding:8px 18px; font-size:13px; border:1px solid #e0e4ec;">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>-->

        <div class="">
           <table id="counselorsTable" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Counselor</th>
                        <th>Contact</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th class="text-center" width="130">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($counselors as $i => $c)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle mr-3">
                                {{ strtoupper(substr($c->first_name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600; color:#1a2e2d;">{{ $c->full_name }}</div>
                                <div style="font-size:12px; color:#9e9e9e;">{{ $c->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $c->phone }}</td>
                    <td>
                        <span style="background:#eaf7f5; color:#087a7f; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">
                            {{ $c->specialization }}
                        </span>
                    </td>
                    <td>
                        @if($c->status === 'active')
                            <span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Active</span>
                        @elseif($c->status === 'inactive')
                            <span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Inactive</span>
                        @else
                            <span class="badge-pending"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Pending</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#aaa;">{{ $c->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                             
                             <a href="{{ route('admin.counselors.show', $c) }}"
                               class="btn-action" title="View"
                               style="background:#eaf7f5; color:#087a7f;">
                                <i class="fas fa-eye"></i>
                            </a>
                           
                            <a href="{{ route('admin.counselors.edit', $c) }}"
                               class="btn-action btn-edit" title="Edit" style="background:#eaf7f5; color:#087a7f;">
                                <i class="fas fa-pen"></i>
                            </a>
                          
                            <form action="{{ route('admin.counselors.toggle', $c) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn-action {{ $c->status==='active' ? 'btn-toggle-active' : 'btn-toggle-inactive' }}"
                                    title="{{ $c->status==='active' ? 'Set Inactive' : 'Set Active' }}">
                                    <i class="fas {{ $c->status==='active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                           
<form action="{{ route('admin.counselors.destroy', $c) }}" method="POST" id="delete-form-{{ $c->id }}">
    @csrf @method('DELETE')
    <button type="button" class="btn-action btn-delete" title="Delete"
            onclick="openDeleteModal('{{ $c->id }}', '{{ $c->full_name }}')">
        <i class="fas fa-trash"></i>
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
<div class="modal fade" id="deleteCounselorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-trash-alt" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Delete Counselor?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    You're about to permanently remove
                    <strong id="deleteCounselorName" style="color:#1a1a2e;"></strong>.
                    This action cannot be undone and will also remove their availability and expertise records.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn" class="btn flex-fill"
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
    $('#counselorsTable').DataTable({
        responsive: true,
		autoWidth:false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[0, 'asc']],
        language: {
            search: '',
            searchPlaceholder: 'Search counselors...',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ counselors',
            emptyTable: '<i class="fas fa-user-md" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i><p class="text-muted mb-2">No counselors found.</p><a href="{{ route('admin.counselors.create') }}" class="btn btn-primary">Add First Counselor</a>',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last:  '<i class="fas fa-angle-double-right"></i>',
                next:  '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
            }
        },
        columnDefs: [
            { orderable: false, targets: [-1] } // disable sort on Actions column
        ]
    });
});

let pendingDeleteFormId = null;

function openDeleteModal(id, name) {
    pendingDeleteFormId = 'delete-form-' + id;
    document.getElementById('deleteCounselorName').textContent = name;
    $('#deleteCounselorModal').modal('show');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (pendingDeleteFormId) {
        document.getElementById(pendingDeleteFormId).submit();
    }
});
</script>
@endpush
