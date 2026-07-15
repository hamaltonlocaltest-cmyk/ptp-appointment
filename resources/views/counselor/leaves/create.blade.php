@extends('counselor.layouts.app')
@section('title', 'Add Leave')
@section('page-title', 'Add Leave')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('counselor.leaves.index') }}">My Leaves</a></li>
    <li class="breadcrumb-item active">Add</li>
@endsection

@section('content')
<div class="container-fluid py-2">
<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="mb-4">
        <h4 style="font-weight:700; color:#1a1a2e;">Add Leave</h4>
        <p class="text-muted mb-0" style="font-size:13px;">
            Mark yourself unavailable for a single day or a date range. Counselees won't be able to book you on these dates.
        </p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">
            <form action="{{ route('counselor.leaves.store') }}" method="POST" id="leaveForm">
                @csrf

                <div class="form-group mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="isRange">
                        <label class="custom-control-label" for="isRange" style="font-size:13px; font-weight:600; color:#555;">
                            This is a date range (multiple consecutive days)
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" id="startLabel" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                                Date
                            </label>
                            <input type="date" name="start_date" id="startDate" value="{{ old('start_date') }}"
                                   min="{{ now()->toDateString() }}" required
                                   class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;">
                        </div>
                    </div>
                    <div class="col-md-6" id="endDateWrapper" style="display:none;">
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                                Through
                            </label>
                            <input type="date" name="end_date" id="endDate" value="{{ old('end_date') }}"
                                   min="{{ now()->toDateString() }}"
                                   class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                        Reason <span class="text-muted" style="font-weight:400; text-transform:none;">(optional)</span>
                    </label>
                    <input type="text" name="reason" value="{{ old('reason') }}" maxlength="255"
                           class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;"
                           placeholder="e.g. Personal leave, Travel, Medical">
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('counselor.leaves.index') }}" class="btn btn-light"
                       style="border-radius:7px; border:1px solid #e0e4ec; padding:10px 22px;">
                        <i class="fas fa-arrow-left mr-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn"
                            style="background:#1b5e20; color:#fff; border-radius:7px; padding:10px 30px; font-weight:700; font-size:15px;">
                        <i class="fas fa-calendar-minus mr-2"></i> Add Leave
                    </button>
                </div>
            </form>
        </div>
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
    startLabel.textContent = this.checked ? 'From' : 'Date';
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
