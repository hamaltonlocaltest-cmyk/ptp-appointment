@extends('admin.layouts.app')
@section('title', 'Apply Counselor Leave')
@section('page-title', 'Apply Counselor Leave')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselor-leaves.index') }}">Counselor Leaves</a></li>
    <li class="breadcrumb-item active">Apply</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;">
                    <i class="fas fa-calendar-minus mr-2" style="color:#1a237e;"></i> Apply Leave on Behalf of a Counselor
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

                <form action="{{ route('admin.counselor-leaves.store') }}" method="POST" id="leaveForm">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Counselor <span class="text-danger">*</span></label>
                        <select name="counselor_id" class="form-control @error('counselor_id') is-invalid @enderror" required>
                            <option value="">Select a counselor</option>
                            @foreach($counselors as $c)
                            <option value="{{ $c->id }}" {{ old('counselor_id') == $c->id ? 'selected' : '' }}>{{ $c->full_name }}</option>
                            @endforeach
                        </select>
                        @error('counselor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="isRange">
                            <label class="custom-control-label" for="isRange">
                                This is a date range (multiple consecutive days)
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" id="startLabel">Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="startDate"
                                       value="{{ old('start_date') }}" min="{{ now()->toDateString() }}" required
                                       class="form-control @error('start_date') is-invalid @enderror">
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6" id="endDateWrapper" style="display:none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Through</label>
                                <input type="date" name="end_date" id="endDate"
                                       value="{{ old('end_date') }}" min="{{ now()->toDateString() }}"
                                       class="form-control @error('end_date') is-invalid @enderror">
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Reason <span class="text-muted" style="font-weight:400;">(optional)</span></label>
                        <input type="text" name="reason" value="{{ old('reason') }}" maxlength="255"
                               class="form-control" placeholder="e.g. Personal leave, Travel, Medical">
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="{{ route('admin.counselor-leaves.index') }}" class="btn btn-secondary action-btn">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary action-btn" style="font-weight:600;">
                            <i class="fas fa-save mr-1"></i> Apply Leave
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
const isRange = document.getElementById('isRange');
const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const endDateWrapper = document.getElementById('endDateWrapper');
const startLabel = document.getElementById('startLabel');

isRange.addEventListener('change', function () {
    endDateWrapper.style.display = this.checked ? '' : 'none';
    startLabel.innerHTML = this.checked ? 'From <span class="text-danger">*</span>' : 'Date <span class="text-danger">*</span>';
    if (!this.checked) endDate.value = '';
});

startDate.addEventListener('change', function () {
    endDate.min = this.value;
    if (!isRange.checked) endDate.value = this.value;
});

document.getElementById('leaveForm').addEventListener('submit', function (e) {
    if (!isRange.checked) endDate.value = startDate.value;
});
</script>
@endpush
