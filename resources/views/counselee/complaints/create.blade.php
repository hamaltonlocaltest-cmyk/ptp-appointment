@extends('counselee.layouts.app')
@section('title', 'File a Complaint')
@section('page-title', 'File a Complaint')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('counselee.complaints.index') }}">Complaints</a></li>
    <li class="breadcrumb-item active">New</li>
@endsection

@section('content')
<div class="container py-4">
<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="mb-4">
        <h4 style="font-weight:700; color:#1a1a2e;">File a Complaint</h4>
        <p class="text-muted mb-0" style="font-size:13px;">
            Let us know if something went wrong with a session or your experience with us. We'll review it and follow up.
        </p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <div class="card" style="border-radius:14px; border:none; box-shadow:0 4px 20px rgba(0,0,0,.08);">
        <div class="card-body p-4">
            <form action="{{ route('counselee.complaints.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                        Related Appointment <span class="text-muted" style="font-weight:400; text-transform:none;">(optional)</span>
                    </label>
                    <select name="appointment_id" class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;">
                        <option value="">— Not related to a specific appointment —</option>
                        @foreach($appointments as $appt)
                        <option value="{{ $appt->id }}" {{ old('appointment_id') == $appt->id ? 'selected' : '' }}>
                            {{ $appt->counselType->name }} — {{ $appt->appointment_date->format('M j, Y') }} ({{ ucfirst($appt->status) }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                        Subject
                    </label>
                    <input type="text" name="subject" value="{{ old('subject') }}" maxlength="150" required
                           class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:8px 12px;"
                           placeholder="Briefly summarize the issue">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-size:12px; font-weight:600; color:#555; text-transform:uppercase; letter-spacing:.4px;">
                        Description
                    </label>
                    <textarea name="description" rows="6" maxlength="2000" required
                              class="form-control" style="border-radius:8px; border:1.5px solid #e0e4ec; font-size:13px; padding:10px 14px;"
                              placeholder="Please describe what happened in as much detail as possible...">{{ old('description') }}</textarea>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('counselee.complaints.index') }}" class="btn btn-light"
                       style="border-radius:7px; border:1px solid #e0e4ec; padding:10px 22px;">
                        <i class="fas fa-arrow-left mr-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn"
                            style="background:#D30404; color:#fff; border-radius:7px; padding:10px 30px; font-weight:700; font-size:15px;">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</div>
</div>
@endsection
