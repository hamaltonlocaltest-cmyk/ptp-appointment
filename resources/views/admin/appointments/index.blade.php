@extends('admin.layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('breadcrumb')
    <li class="breadcrumb-item active">Appointments</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row mb-3">
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselee">
            <h3>{{ $counts['total'] }}</h3><p>Total Appointments</p>
            <i class="fas fa-calendar-check stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-active">
            <h3>{{ $counts['confirmed'] }}</h3><p>Confirmed</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-counselor">
            <h3>{{ $counts['completed'] }}</h3><p>Completed</p>
            <i class="fas fa-check-double stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-3 col-6 mb-3">
        <div class="stat-card bg-inactive">
            <h3>{{ $counts['cancelled'] }}</h3><p>Cancelled</p>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="font-weight:600; flex:auto">
            <i class="fas fa-calendar-check mr-2"></i> Appointment List
        </span>
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i> Book Appointment
        </a>
    </div>
    <div class="card-body">

        <form method="GET" action="{{ route('admin.appointments.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Search</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control"
                               placeholder="Counsellee or counsellor name/email..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending"   {{ request('status')=='pending'   ?'selected':'' }}>Pending</option>
                        <option value="confirmed" {{ request('status')=='confirmed' ?'selected':'' }}>Confirmed</option>
                        <option value="completed" {{ request('status')=='completed' ?'selected':'' }}>Completed</option>
                        <option value="cancelled" {{ request('status')=='cancelled' ?'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Mode</label>
                    <select name="mode" class="form-control">
                        <option value="">All Modes</option>
                        <option value="Online"    {{ request('mode')=='Online'    ?'selected':'' }}>Online</option>
                        <option value="In person" {{ request('mode')=='In person' ?'selected':'' }}>In person</option>
                        <option value="Both"      {{ request('mode')=='Both'      ?'selected':'' }}>Both</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 d-flex" style="gap:8px;">
                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius:7px; padding:8px 18px; font-size:13px;">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-light" style="border-radius:7px; padding:8px 18px; font-size:13px; border:1px solid #e0e4ec;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
           <table id="appointmentsTable" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Counsellee</th>
                        <th>Counselor</th>
                        <th>Type</th>
                        <th>Date &amp; Time</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th class="text-center" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($appointments as $i => $a)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:600; color:#1a1a2e;">{{ $a->counselee->full_name }}</div>
                        <div style="font-size:12px; color:#9e9e9e;">{{ $a->counselee->email }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1a1a2e;">{{ $a->counselor->full_name }}</div>
                        <div style="font-size:12px; color:#9e9e9e;">{{ $a->counselor->email }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $a->counselType->name }}</td>
                    <td style="font-size:13px;">
                        {{ $a->appointment_date->format('M d, Y') }}<br>
                        <span style="color:#9e9e9e; font-size:12px;">{{ $a->formatted_time }}</span>
                    </td>
                    <td>
                        <span style="background:#e8eaf6; color:#1a237e; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500;">
                            <i class="fas fa-{{ $a->mode === 'Online' ? 'video' : 'map-marker-alt' }} mr-1"></i>{{ $a->mode }}
                        </span>
                    </td>
                    <td>
                        @php $badge = ['pending'=>'badge-pending','confirmed'=>'badge-active','cancelled'=>'badge-inactive','completed'=>'badge-active'][$a->status] ?? 'badge-pending'; @endphp
                        <span class="{{ $badge }}"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>{{ ucfirst($a->status) }}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            <a href="{{ route('admin.appointments.show', $a) }}" class="btn-action" title="View" style="background:#e3f2fd; color:#1565c0;">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(in_array($a->status, ['pending','confirmed']))
                            <a href="{{ route('admin.appointments.reschedule.edit', $a) }}" class="btn-action" title="Reschedule" style="background:#ede7f6; color:#4527a0;">
                                <i class="fas fa-calendar-alt"></i>
                            </a>
                            <form action="{{ route('admin.appointments.cancel', $a) }}" method="POST" id="cancel-form-{{ $a->id }}">
                                @csrf
                                <button type="button" class="btn-action btn-delete" title="Cancel"
                                        onclick="openCancelModal('{{ $a->id }}', '{{ $a->counselType->name }}', '{{ $a->counselee->full_name }}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-calendar-check" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-2">No appointments found.</p>
                        <a href="{{ route('admin.appointments.create') }}" class="btn btn-sm btn-primary">Book First Appointment</a>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-calendar-times" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Cancel this appointment?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:16px;">
                    Both the counsellee and counselor will be notified by email.
                </p>
                <div class="text-left" style="background:#f8f9fc; border-radius:10px; padding:14px 16px; font-size:13px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Type</span>
                        <strong id="cancelType" style="color:#1a1a2e;"></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Counsellee</span>
                        <strong id="cancelCounselee" style="color:#1a1a2e;"></strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                        style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                    Keep Appointment
                </button>
                <button type="button" id="confirmCancelBtn" class="btn flex-fill"
                        style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                    <i class="fas fa-times mr-1"></i> Yes, Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#appointmentsTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        searching: false,
        language: {
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ appointments',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last:  '<i class="fas fa-angle-double-right"></i>',
                next:  '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
            }
        },
        columnDefs: [
            { orderable: false, targets: [-1] }
        ]
    });
});

let pendingCancelFormId = null;
function openCancelModal(id, type, counselee) {
    pendingCancelFormId = 'cancel-form-' + id;
    document.getElementById('cancelType').textContent = type;
    document.getElementById('cancelCounselee').textContent = counselee;
    $('#cancelModal').modal('show');
}
document.getElementById('confirmCancelBtn').addEventListener('click', function () {
    if (pendingCancelFormId) document.getElementById(pendingCancelFormId).submit();
});
</script>
@endpush
