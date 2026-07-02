@extends('admin.layouts.app')
@section('title', 'Edit Counsel Type')
@section('page-title', 'Edit Counsel Type')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.masters.counsel-types.index') }}">Counsel Types</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                <span style="color:#1a237e; font-size:15px; font-weight:600;">
                    <i class="fas fa-edit mr-2" style="color:#1a237e;"></i> Edit: {{ $counselType->name }}
                </span>
                @if($counselType->status === 'active')
                    <span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Active</span>
                @else
                    <span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Inactive</span>
                @endif
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2 pl-3">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- Preview Banner --}}
                <div class="d-flex align-items-center p-3 mb-4"
                     id="previewBanner"
                     style="background:#e8eaf6; border-radius:10px; border:1.5px solid #c5cae9;">
                    <div id="previewIconBox"
                         style="width:48px; height:48px; border-radius:10px; background:{{ $counselType->color }}22;
                                display:flex; align-items:center; justify-content:center; margin-right:14px; flex-shrink:0;">
                        <i id="previewIcon" class="{{ $counselType->icon }}"
                           style="font-size:22px; color:{{ $counselType->color }};"></i>
                    </div>
                    <div>
                        <div id="previewName" style="font-size:16px; font-weight:700; color:#1a1a2e;">{{ $counselType->name }}</div>
                        <div id="previewDesc" style="font-size:12px; color:#9e9e9e; margin-top:2px;">{{ $counselType->description }}</div>
                    </div>
                </div>

                <form id="editForm" action="{{ route('admin.masters.counsel-types.update', $counselType) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Type Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="nameInput"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $counselType->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Slug: <code id="slugPreview">{{ $counselType->slug }}</code></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="active"   {{ old('status',$counselType->status)=='active'   ?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ old('status',$counselType->status)=='inactive' ?'selected':'' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="descInput" class="form-control" rows="3"
                                  placeholder="Brief description of this counsel type...">{{ old('description', $counselType->description) }}</textarea>
                        <small class="text-muted"><span id="descCount">{{ strlen($counselType->description ?? '') }}</span>/500 characters</small>
                    </div>

                    <div class="row">
                        {{-- Icon Picker --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <select name="icon" id="iconSelect" class="form-control">
                                    @foreach($icons as $cls => $label)
                                        <option value="{{ $cls }}"
                                            {{ old('icon',$counselType->icon)==$cls ?'selected':'' }}>
                                            {{ $label }} ({{ $cls }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2 d-flex flex-wrap" style="gap:8px;" id="iconGrid">
                                    @foreach($icons as $cls => $label)
                                    <div class="icon-option"
                                         data-icon="{{ $cls }}"
                                         title="{{ $label }}"
                                         style="width:38px; height:38px; border-radius:8px; cursor:pointer;
                                                border:2px solid {{ old('icon',$counselType->icon)==$cls ? '#1a237e' : '#e0e4ec' }};
                                                background:{{ old('icon',$counselType->icon)==$cls ? '#e8eaf6' : '#fff' }};
                                                display:flex; align-items:center; justify-content:center;">
                                        <i class="{{ $cls }}" style="font-size:16px; color:#555;"></i>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Color Picker --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Color <span class="text-danger">*</span></label>
                                <select name="color" id="colorSelect" class="form-control">
                                    @foreach($colors as $hex => $label)
                                        <option value="{{ $hex }}"
                                            {{ old('color',$counselType->color)==$hex ?'selected':'' }}>
                                            {{ $label }} ({{ $hex }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2 d-flex flex-wrap" style="gap:8px;" id="colorGrid">
                                    @foreach($colors as $hex => $label)
                                    <div class="color-option"
                                         data-color="{{ $hex }}"
                                         title="{{ $label }}"
                                         style="width:38px; height:38px; border-radius:8px; background:{{ $hex }};
                                                cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.2);
                                                border:3px solid {{ old('color',$counselType->color)==$hex ? '#000' : 'transparent' }};">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $counselType->sort_order) }}"
                               min="0" style="width:150px;">
                        <small class="text-muted">Lower numbers appear first in lists.</small>
                    </div>

                    {{-- Quick Toggle --}}
                    <div class="d-flex align-items-center p-3 mb-3"
                         style="background:#f8f9fc; border-radius:8px; border:1px solid #e0e4ec;">
                        <div style="flex:1;">
                            <div style="font-size:13px; font-weight:600; color:#444;">Quick Status Toggle</div>
                            <div style="font-size:12px; color:#9e9e9e; margin-top:2px;">
                                Current: <strong>{{ ucfirst($counselType->status) }}</strong>
                            </div>
                        </div>
                        <button type="button"
                            onclick="document.getElementById('toggleForm').submit()"
                            class="btn btn-sm {{ $counselType->status==='active' ? 'btn-danger' : 'btn-success' }}"
                            style="border-radius:20px; padding:6px 18px; font-size:12px; font-weight:600;">
                            <i class="fas {{ $counselType->status==='active' ? 'fa-times-circle' : 'fa-check-circle' }} mr-1"></i>
                            {{ $counselType->status==='active' ? 'Set Inactive' : 'Set Active' }}
                        </button>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="{{ route('admin.masters.counsel-types.index') }}"
                           class="btn btn-light" style="border-radius:7px; padding:9px 22px; border:1px solid #e0e4ec; font-size:13px;">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                        <div style="display:flex; gap:8px;">
                            <button type="button"
                                data-toggle="modal" data-target="#deleteModal"
                                class="btn btn-danger"
                                style="border-radius:7px; padding:9px 22px; font-size:13px; font-weight:600;">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                            <button type="submit" form="editForm" class="btn"
                                    style="background:#1a237e; color:#fff; border-radius:7px; padding:9px 26px; font-size:13px; font-weight:600;">
                                <i class="fas fa-save mr-1"></i> Update
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Outside forms --}}
                <form id="toggleForm" action="{{ route('admin.masters.counsel-types.toggle', $counselType) }}" method="POST" style="display:none;">
                    @csrf @method('PATCH')
                </form>
                <form id="deleteForm" action="{{ route('admin.masters.counsel-types.destroy', $counselType) }}" method="POST" style="display:none;">
                    @csrf @method('DELETE')
                </form>

            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
                        <div class="modal-body text-center" style="padding:32px 28px 20px;">
                            <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                                <i class="fas fa-trash-alt" style="font-size:24px; color:#c62828;"></i>
                            </div>
                            <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Delete Counsel Type?</h5>
                            <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                                You're about to permanently remove
                                <strong style="color:#1a1a2e;">{{ $counselType->name }}</strong>.
                                This action cannot be undone.
                            </p>
                        </div>
                        <div class="modal-footer" style="border-top:1px solid #f0f0f4; padding:16px 24px; display:flex; gap:10px;">
                            <button type="button" class="btn btn-light flex-fill" data-dismiss="modal"
                                    style="border-radius:8px; border:1px solid #e0e4ec; font-size:13px; font-weight:600; padding:9px;">
                                Cancel
                            </button>
                            <button type="button" class="btn flex-fill" onclick="document.getElementById('deleteForm').submit();"
                                    style="background:#c62828; color:#fff; border-radius:8px; font-size:13px; font-weight:600; padding:9px;">
                                <i class="fas fa-trash mr-1"></i> Yes, Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const nameInput      = document.getElementById('nameInput');
    const descInput      = document.getElementById('descInput');
    const iconSelect     = document.getElementById('iconSelect');
    const colorSelect    = document.getElementById('colorSelect');
    const previewIcon    = document.getElementById('previewIcon');
    const previewIconBox = document.getElementById('previewIconBox');
    const previewName    = document.getElementById('previewName');
    const previewDesc    = document.getElementById('previewDesc');
    const slugPreview    = document.getElementById('slugPreview');
    const descCount      = document.getElementById('descCount');

    function slugify(text) {
        return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }

    function updatePreview() {
        previewName.textContent     = nameInput.value || 'Counsel Type Name';
        previewDesc.textContent     = descInput.value || 'Description preview';
        previewIcon.className       = iconSelect.value;
        previewIcon.style.color     = colorSelect.value;
        previewIconBox.style.background = colorSelect.value + '22';
        slugPreview.textContent     = slugify(nameInput.value) || '{{ $counselType->slug }}';
        descCount.textContent       = descInput.value.length;
    }

    nameInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);
    iconSelect.addEventListener('change', function() {
        document.querySelectorAll('.icon-option').forEach(el => { el.style.border='2px solid #e0e4ec'; el.style.background='#fff'; });
        const sel = document.querySelector(`.icon-option[data-icon="${this.value}"]`);
        if (sel) { sel.style.border='2px solid #1a237e'; sel.style.background='#e8eaf6'; }
        updatePreview();
    });
    colorSelect.addEventListener('change', function() {
        document.querySelectorAll('.color-option').forEach(el => el.style.border='3px solid transparent');
        const sel = document.querySelector(`.color-option[data-color="${this.value}"]`);
        if (sel) sel.style.border = '3px solid #000';
        updatePreview();
    });
    document.querySelectorAll('.icon-option').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.icon-option').forEach(e => { e.style.border='2px solid #e0e4ec'; e.style.background='#fff'; });
            this.style.border = '2px solid #1a237e'; this.style.background = '#e8eaf6';
            iconSelect.value = this.dataset.icon;
            updatePreview();
        });
    });
    document.querySelectorAll('.color-option').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(e => e.style.border='3px solid transparent');
            this.style.border = '3px solid #000';
            colorSelect.value = this.dataset.color;
            updatePreview();
        });
    });

</script>
@endpush
