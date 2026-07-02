@extends('admin.layouts.app')
@section('title', 'Counselee Details')
@section('page-title', 'Counselee Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselees.index') }}">Counselees</a></li>
    <li class="breadcrumb-item active">{{ $counselee->full_name }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

       
        <div class="card mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle mr-3"
                             style="width:64px; height:64px; font-size:24px; background:#{{ substr(md5($counselee->email),0,6) }};">
                            {{ strtoupper(substr($counselee->first_name,0,1)) }}
                        </div>
                        <div>
                            <h4 class="mb-0" style="color:#1a1a2e; font-weight:700;">
                                {{ $counselee->title ? $counselee->title . '. ' : '' }}{{ $counselee->full_name }}
                            </h4>
                            <div style="color:#9e9e9e; font-size:13px;">{{ $counselee->email }}</div>
                            <div class="mt-2">
                                @if($counselee->status === 'active')
                                    <span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Active</span>
                                @else
                                    <span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Inactive</span>
                                @endif
                                <span class="ml-2" style="font-size:12px; color:#aaa;">
                                    Registered {{ $counselee->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex" style="gap:8px;">
                        <a href="{{ route('admin.appointments.create', ['counselee_id' => $counselee->id]) }}"
                           class="btn" style="background:#1f8582; color:#fff;">
                            <i class="fas fa-calendar-plus mr-1"></i> Book Appointment
                        </a>
                        <a href="{{ route('admin.counselees.edit', $counselee) }}"
                           class="btn btn-primary ">
                            <i class="fas fa-pen mr-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.counselees.destroy', $counselee) }}" method="POST" id="deleteForm">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;">
                    <i class="fas fa-user mr-2"></i> Personal Information
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Email Address</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->email }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Telephone 1</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->telephone1 ?: '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Telephone 2</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->telephone2 ?: '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Birthdate</div>
                        <div style="font-weight:600; color:#333;">
                            {{ $counselee->birthdate ? $counselee->birthdate->format('M d, Y') : '—' }}
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Age</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->age ?: '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Gender</div>
                        <div style="font-weight:600; color:#333; text-transform:capitalize;">{{ $counselee->gender ?: '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Marital Status</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->marital_status ?: '—' }}</div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Address</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->address ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

       
        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;">
                    <i class="fas fa-child mr-2"></i> Children
                </span>
            </div>
            <div class="card-body p-0">
                @if($counselee->children->isNotEmpty())
                <table class="table mb-0">
                    <thead style="background:#f8f9fc;">
                        <tr><th>Name</th><th>Gender</th><th>Age</th></tr>
                    </thead>
                    <tbody>
                        @foreach($counselee->children as $child)
                        <tr>
                            <td>{{ $child->name }}</td>
                            <td>{{ $child->gender ?: '—' }}</td>
                            <td>{{ $child->age ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted py-4">No children on record.</div>
                @endif
            </div>
        </div>

       
        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;">
                    <i class="fas fa-notes-medical mr-2"></i> Medical History
                </span>
            </div>
            <div class="card-body p-0">
                @if($counselee->medicalHistories->isNotEmpty())
                <table class="table mb-0">
                    <thead style="background:#f8f9fc;">
                        <tr><th width="35%">Condition</th><th>Details</th></tr>
                    </thead>
                    <tbody>
                        @foreach($counselee->medicalHistories as $hist)
                        <tr>
                            <td>{{ $hist->condition }}</td>
                            <td>{{ $hist->details ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted py-4">No medical history on record.</div>
                @endif
            </div>
        </div>

      
        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;">
                    <i class="fas fa-hand-holding-heart mr-2"></i> Areas Seeking Counselling
                </span>
            </div>
            <div class="card-body p-4">
                @if($counselee->counselTypes->isNotEmpty())
                    @foreach($counselee->counselTypes as $type)
                    <span class="badge mb-1" style="background:{{ $type->color ?: '#f3e5f5' }}22; color:{{ $type->color ?: '#4a148c' }}; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:500; margin-right:6px;">
                        @if($type->icon)<i class="{{ $type->icon }} mr-1"></i>@endif
                        {{ $type->name }}
                    </span>
                    @endforeach
                @else
                    <div class="text-muted">No areas selected.</div>
                @endif
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;">
                    <i class="fas fa-share-alt mr-2"></i> Referral & Previous Counselling
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Referred By</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->referral ?: '—' }}</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div style="font-size:12px; color:#9e9e9e;">Previous Counselling</div>
                        <div style="font-weight:600; color:#333;">{{ $counselee->previous_counselling ?: '—' }}</div>
                    </div>
                </div>
                @if($counselee->previous_counselling === 'Yes' && $counselee->previous_counselling_details)
                <div>
                    <div style="font-size:12px; color:#9e9e9e;">Details</div>
                    <div style="font-weight:500; color:#333;">{{ $counselee->previous_counselling_details }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="mb-4">
            <a href="{{ route('admin.counselees.index') }}"
               class="btn btn-secondary" style="font-size: 18px; font-weight: 600;">
                <i class="fas fa-arrow-left mr-1"></i> Back to List
            </a>
        </div>

    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px; border:none; overflow:hidden;">
            <div class="modal-body text-center" style="padding:32px 28px 20px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#fdecea; display:flex; align-items:center; justify-content:center; margin:0 auto 18px;">
                    <i class="fas fa-trash-alt" style="font-size:24px; color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700; color:#1a1a2e; margin-bottom:8px;">Delete Counselee?</h5>
                <p style="font-size:13.5px; color:#6b6b76; margin-bottom:0;">
                    You're about to permanently remove
                    <strong style="color:#1a1a2e;">{{ $counselee->full_name }}</strong>.
                    This action cannot be undone and will also remove their appointment history.
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
@endsection