@extends('admin.layouts.app')
@section('title', 'Counselor Leaves')
@section('page-title', 'Counselor Leaves')
@section('breadcrumb')
    <li class="breadcrumb-item active">Counselor Leaves</li>
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-calendar-minus mr-2"></i> Counselor Leave Records
        </span>
        <a href="{{ route('admin.counselor-leaves.create') }}"
           class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Apply Leave
        </a>
    </div>
    <div class="card-body">

        <form method="GET" action="{{ route('admin.counselor-leaves.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Counselor</label>
                    <select name="counselor_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Counselors</option>
                        @foreach($counselors as $c)
                        <option value="{{ $c->id }}" {{ request('counselor_id') == $c->id ? 'selected' : '' }}>{{ $c->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(request('counselor_id'))
                <div class="col-md-3 mb-2">
                    <a href="{{ route('admin.counselor-leaves.index') }}" class="btn btn-sm btn-light" style="border-radius:7px; padding:8px 18px; font-size:13px; border:1px solid #e0e4ec;">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                </div>
                @endif
            </div>
        </form>

        <div class="">
            <table id="leavesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Counselor</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Filed By</th>
                        <th class="text-center" width="80">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($leaves as $i => $leave)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td style="font-weight:600;">{{ $leave->counselor->full_name ?? 'Deleted Counselor' }}</td>
                    <td style="font-size:13px;">
                        @if($leave->start_date->eq($leave->end_date))
                            {{ $leave->start_date->format('M j, Y') }}
                        @else
                            {{ $leave->start_date->format('M j, Y') }} &rarr; {{ $leave->end_date->format('M j, Y') }}
                        @endif
                    </td>
                    <td style="font-size:13px; color:#666;">{{ $leave->reason ?: '—' }}</td>
                    <td>
                        <span style="background:{{ $leave->created_by === 'admin' ? '#e8eaf6' : '#e8f5e9' }}; color:{{ $leave->created_by === 'admin' ? '#1a237e' : '#1b5e20' }}; padding:3px 12px; border-radius:20px; font-size:11px; font-weight:700;">
                            {{ ucfirst($leave->created_by) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <form action="{{ route('admin.counselor-leaves.destroy', $leave) }}" method="POST" id="delete-form-{{ $leave->id }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action btn-delete" title="Delete"
                                        onclick="openDeleteModal('{{ $leave->id }}', '{{ $leave->counselor->full_name ?? 'this counselor' }}')">
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

<div class="modal fade" id="deleteLeaveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-trash-alt" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Remove this leave record?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    <strong id="deleteLeaveCounselor" style="color:#1a1a2e;"></strong> will become bookable again on these dates.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteLeaveBtn" class="btn flex-fill"
                        style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-trash mr-1"></i> Yes, Remove
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#leavesTable').DataTable({
        responsive: true,
        autoWidth:false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[2, 'desc']],
        language: {
            search: '',
            searchPlaceholder: 'Search leave records...',
            info: 'Showing _START_ to _END_ of _TOTAL_ leave records',
            emptyTable: '<i class="fas fa-calendar-check" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i><p class="text-muted mb-0">No leave records found.</p>',
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
    document.getElementById('deleteLeaveCounselor').textContent = name;
    $('#deleteLeaveModal').modal('show');
}

document.getElementById('confirmDeleteLeaveBtn').addEventListener('click', function () {
    if (pendingDeleteFormId) {
        document.getElementById(pendingDeleteFormId).submit();
    }
});
</script>
@endpush
