@extends('admin.layouts.app')
@section('title', 'Counsel Types')
@section('page-title', 'Counsel Types')
@section('breadcrumb')
    <li class="breadcrumb-item active">Masters</li>
    <li class="breadcrumb-item active">Counsel Types</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row mb-3">
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
            <h3>{{ $counts['total'] }}</h3>
            <p>Total Types</p>
            <i class="fas fa-list stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
            <h3>{{ $counts['active'] }}</h3>
            <p>Active</p>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-lg-4 col-6 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#b71c1c,#c62828);">
            <h3>{{ $counts['inactive'] }}</h3>
            <p>Inactive</p>
            <i class="fas fa-times-circle stat-icon"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
        <span style="color:#1a237e; font-size:15px; font-weight:600;">
            <i class="fas fa-comments mr-2" style="color:#1a237e;"></i> Counsel Type List
        </span>
        <a href="{{ route('admin.masters.counsel-types.create') }}"
           class="btn btn-sm" style="background:#1a237e; color:#fff; border-radius:7px; padding:7px 18px; font-size:13px; font-weight:600;">
            <i class="fas fa-plus mr-1"></i> Add Counsel Type
        </a>
    </div>
    <div class="card-body">

        {{-- Filters --}}
        <!--<form method="GET" action="{{ route('admin.masters.counsel-types.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Search</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search counsel type name..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status')=='active'   ?'selected':'' }}>Active</option>
                        <option value="inactive" {{ request('status')=='inactive' ?'selected':'' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 d-flex" style="gap:8px;">
                    <button type="submit" class="btn btn-sm"
                            style="background:#1a237e; color:#fff; border-radius:7px; padding:8px 18px; font-size:13px;">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.masters.counsel-types.index') }}"
                       class="btn btn-sm btn-light"
                       style="border-radius:7px; padding:8px 18px; font-size:13px; border:1px solid #e0e4ec;">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>-->

        <div class="table-responsive">
            <table id="counselTypesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="60">Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th width="80">Order</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-center" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($counselTypes as $i => $type)
                <tr>
                    <td style="color:#aaa;">{{ $i + 1 }}</td>
                    <td>
                        <div style="width:38px; height:38px; border-radius:8px; background:{{ $type->color }}22;
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="{{ $type->icon }}" style="color:{{ $type->color }}; font-size:16px;"></i>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1a1a2e;">{{ $type->name }}</div>
                        <div style="width:10px; height:10px; border-radius:50%; background:{{ $type->color }};
                                    display:inline-block; margin-top:4px;"></div>
                        <span style="font-size:11px; color:#9e9e9e; margin-left:4px;">{{ $type->color }}</span>
                    </td>
                    <td>
                        <code style="background:#f4f6f9; padding:2px 8px; border-radius:4px; font-size:12px; color:#555;">
                            {{ $type->slug }}
                        </code>
                    </td>
                    <td style="font-size:13px; color:#666; max-width:200px;">
                        {{ $type->description ? Str::limit($type->description, 60) : '—' }}
                    </td>
                    <td class="text-center">
                        <span style="background:#e8eaf6; color:#1a237e; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                            {{ $type->sort_order }}
                        </span>
                    </td>
                    <td>
                        @if($type->status === 'active')
                            <span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Active</span>
                        @else
                            <span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Inactive</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#aaa;">{{ $type->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:5px;">
                            <a href="{{ route('admin.masters.counsel-types.edit', $type) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.masters.counsel-types.toggle', $type) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="btn-action {{ $type->status==='active' ? 'btn-toggle-active' : 'btn-toggle-inactive' }}"
                                    title="{{ $type->status==='active' ? 'Set Inactive' : 'Set Active' }}">
                                    <i class="fas {{ $type->status==='active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                            <button type="button"
                                onclick="confirmDelete('{{ $type->name }}', '{{ route('admin.masters.counsel-types.destroy', $type) }}')"
                                class="btn-action btn-delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="fas fa-comments" style="font-size:46px; color:#e0e0e0; display:block; margin-bottom:12px;"></i>
                        <p class="text-muted mb-2">No counsel types found.</p>
                        <a href="{{ route('admin.masters.counsel-types.create') }}"
                           class="btn btn-sm" style="background:#1a237e; color:#fff; border-radius:7px; padding:7px 18px;">
                            Add First Counsel Type
                        </a>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

       
       
    </div>
</div>

{{-- Hidden delete form --}}
<form id="deleteForm" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#counselTypesTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            search: '',
            searchPlaceholder: 'Search counsel types...',
            info: 'Showing _START_ to _END_ of _TOTAL_ types',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last:  '<i class="fas fa-angle-double-right"></i>',
                next:  '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
            }
        },
        columnDefs: [
            { orderable: false, targets: [1, -1] } // disable sort on Icon and Actions
        ]
    });
});
function confirmDelete(name, url) {
    if (confirm('Delete "' + name + '"?\nThis cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = url;
        form.submit();
    }
}
</script>
@endpush
