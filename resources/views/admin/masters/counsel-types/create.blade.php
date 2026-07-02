@extends('admin.layouts.app')
@section('title', 'Add Counsel Type')
@section('page-title', 'Add Counsel Type')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.masters.counsel-types.index') }}">Counsel Types</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="color:#1a237e; font-size:15px; font-weight:600;">
                    <i class="fas fa-plus-circle mr-2" style="color:#1a237e;"></i> New Counsel Type
                </span>
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

                <form id="createForm" action="{{ route('admin.masters.counsel-types.store') }}" method="POST">
                    @csrf

                    {{-- Preview Banner --}}
                    <div class="d-flex align-items-center p-3 mb-4"
                         id="previewBanner"
                         style="background:#e8eaf6; border-radius:10px; border:1.5px solid #c5cae9;">
                        <div id="previewIconBox"
                             style="width:48px; height:48px; border-radius:10px; background:#1a237e22;
                                    display:flex; align-items:center; justify-content:center; margin-right:14px; flex-shrink:0;">
                            <i id="previewIcon" class="fas fa-comments" style="font-size:22px; color:#1a237e;"></i>
                        </div>
                        <div>
                            <div id="previewName" style="font-size:16px; font-weight:700; color:#1a1a2e;">Counsel Type Name</div>
                            <div id="previewDesc" style="font-size:12px; color:#9e9e9e; margin-top:2px;">Description preview</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Type Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="nameInput"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="e.g. Children Counseling, Pre-Marital" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Slug: <code id="slugPreview">auto-generated</code></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="active"   {{ old('status','active')=='active'   ?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ old('status')=='inactive' ?'selected':'' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="descInput" class="form-control" rows="3"
                                  placeholder="Brief description of this counsel type...">{{ old('description') }}</textarea>
                        <small class="text-muted"><span id="descCount">0</span>/500 characters</small>
                    </div>

                    <div class="row">
                        {{-- Icon Picker --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <select name="icon" id="iconSelect" class="form-control">
                                    @foreach($icons as $cls => $label)
                                        <option value="{{ $cls }}" {{ old('icon','fas fa-comments')==$cls ?'selected':'' }}>
                                            {{ $label }} ({{ $cls }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2 d-flex flex-wrap" style="gap:8px;" id="iconGrid">
                                    @foreach($icons as $cls => $label)
                                    <div class="icon-option {{ old('icon','fas fa-comments')==$cls ? 'selected' : '' }}"
                                         data-icon="{{ $cls }}"
                                         title="{{ $label }}"
                                         style="width:38px; height:38px; border-radius:8px; border:2px solid {{ old('icon','fas fa-comments')==$cls ? '#1a237e' : '#e0e4ec' }};
                                                display:flex; align-items:center; justify-content:center; cursor:pointer;
                                                background:{{ old('icon','fas fa-comments')==$cls ? '#e8eaf6' : '#fff' }};">
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
                                        <option value="{{ $hex }}" {{ old('color','#1a237e')==$hex ?'selected':'' }}>
                                            {{ $label }} ({{ $hex }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2 d-flex flex-wrap" style="gap:8px;" id="colorGrid">
                                    @foreach($colors as $hex => $label)
                                    <div class="color-option {{ old('color','#1a237e')==$hex ? 'selected' : '' }}"
                                         data-color="{{ $hex }}"
                                         title="{{ $label }}"
                                         style="width:38px; height:38px; border-radius:8px; background:{{ $hex }};
                                                border:3px solid {{ old('color','#1a237e')==$hex ? '#000' : 'transparent' }};
                                                cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', 0) }}" min="0" style="width:150px;">
                        <small class="text-muted">Lower numbers appear first in lists.</small>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="{{ route('admin.masters.counsel-types.index') }}"
                           class="btn btn-light" style="border-radius:7px; padding:9px 22px; border:1px solid #e0e4ec; font-size:13px;">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                        <button type="submit" class="btn"
                                style="background:#1a237e; color:#fff; border-radius:7px; padding:9px 26px; font-size:13px; font-weight:600;">
                            <i class="fas fa-save mr-1"></i> Save Counsel Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const nameInput   = document.getElementById('nameInput');
    const descInput   = document.getElementById('descInput');
    const iconSelect  = document.getElementById('iconSelect');
    const colorSelect = document.getElementById('colorSelect');
    const previewIcon = document.getElementById('previewIcon');
    const previewIconBox = document.getElementById('previewIconBox');
    const previewName = document.getElementById('previewName');
    const previewDesc = document.getElementById('previewDesc');
    const slugPreview = document.getElementById('slugPreview');
    const descCount   = document.getElementById('descCount');

    function slugify(text) {
        return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }

    function updatePreview() {
        const name  = nameInput.value || 'Counsel Type Name';
        const desc  = descInput.value || 'Description preview';
        const icon  = iconSelect.value;
        const color = colorSelect.value;

        previewName.textContent = name;
        previewDesc.textContent = desc;
        previewIcon.className   = icon;
        previewIcon.style.color = color;
        previewIconBox.style.background = color + '22';
        slugPreview.textContent = slugify(nameInput.value) || 'auto-generated';
        descCount.textContent   = descInput.value.length;
    }

    nameInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);
    iconSelect.addEventListener('change', function () {
        document.querySelectorAll('.icon-option').forEach(el => {
            el.style.border = '2px solid #e0e4ec';
            el.style.background = '#fff';
        });
        const selected = document.querySelector(`.icon-option[data-icon="${this.value}"]`);
        if (selected) { selected.style.border = '2px solid #1a237e'; selected.style.background = '#e8eaf6'; }
        updatePreview();
    });
    colorSelect.addEventListener('change', function () {
        document.querySelectorAll('.color-option').forEach(el => el.style.border = '3px solid transparent');
        const selected = document.querySelector(`.color-option[data-color="${this.value}"]`);
        if (selected) selected.style.border = '3px solid #000';
        updatePreview();
    });

    // Icon grid click
    document.querySelectorAll('.icon-option').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.icon-option').forEach(e => { e.style.border='2px solid #e0e4ec'; e.style.background='#fff'; });
            this.style.border = '2px solid #1a237e';
            this.style.background = '#e8eaf6';
            iconSelect.value = this.dataset.icon;
            updatePreview();
        });
    });

    // Color grid click
    document.querySelectorAll('.color-option').forEach(function(el) {
        el.addEventListener('click', function() {
            document.querySelectorAll('.color-option').forEach(e => e.style.border = '3px solid transparent');
            this.style.border = '3px solid #000';
            colorSelect.value = this.dataset.color;
            updatePreview();
        });
    });

    updatePreview();
</script>
@endpush
