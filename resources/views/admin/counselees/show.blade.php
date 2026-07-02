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
                        <a href="{{ route('admin.counselees.edit', $counselee) }}"
                           class="btn btn-primary ">
                            <i class="fas fa-pen mr-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.counselees.destroy', $counselee) }}" method="POST"
                              onsubmit="return confirm('Delete {{ $counselee->full_name }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
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
@endsection