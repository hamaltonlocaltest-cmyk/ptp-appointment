@extends('admin.layouts.app')
@section('title', 'Counselor Details')
@section('page-title', 'Counselor Details')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselors.index') }}">Counselors</a></li>
    <li class="breadcrumb-item active">{{ $counselor->full_name }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.counselors.index') }}" class="btn btn-secondary action-btn mb-3 mb-sm-0" style="">
        <i class="fas fa-arrow-left mr-1"></i> Back to List
    </a>
    <div>
        <a href="{{ route('admin.counselors.edit', $counselor) }}" class="btn btn-primary action-btn mb-3 mb-sm-0" style="">
            <i class="fas fa-pen mr-1"></i> Edit
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
   
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-circle mx-auto mb-3" style="width:80px; height:80px; font-size:30px; background:#{{ substr(md5($counselor->email),0,6) }};">
                    {{ strtoupper(substr($counselor->first_name,0,1)) }}
                </div>
                <h5 style="font-weight:600; color:#1a1a2e;">{{ $counselor->full_name }}</h5>
                <p class="text-muted mb-2" style="font-size:13px;">{{ $counselor->specialization }}</p>

                @if($counselor->status === 'active')
                    <span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Active</span>
                @elseif($counselor->status === 'inactive')
                    <span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Inactive</span>
                @else
                    <span class="badge-pending"><i class="fas fa-circle mr-1" style="font-size:7px;"></i>Pending</span>
                @endif

                <hr>

                <div class="text-left" style="font-size:13px;">
                    <p class="mb-2"><i class="fas fa-envelope mr-2" style="color:#9e9e9e;"></i>{{ $counselor->email }}</p>
                    <p class="mb-2"><i class="fas fa-phone mr-2" style="color:#9e9e9e;"></i>{{ $counselor->phone }}</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt mr-2" style="color:#9e9e9e;"></i>{{ $counselor->address }}</p>
                </div>
            </div>
        </div>
    </div>

  
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="font-size:14px;">
                    <i class="fas fa-info-circle mr-2"></i>Professional Details
                </span>
            </div>
            <div class="card-body">
                <dl class="row mb-0" style="font-size:13px;">
                    <dt class="col-sm-4 text-muted">Experience</dt>
                    <dd class="col-sm-8">{{ $counselor->experience_years }} years</dd>

                    <dt class="col-sm-4 text-muted">Mode</dt>
                    <dd class="col-sm-8">{{ $counselor->mode }}</dd>

                    <dt class="col-sm-4 text-muted">Languages</dt>
                    <dd class="col-sm-8">{{ $counselor->languages }}</dd>

                    <dt class="col-sm-4 text-muted">Training Level</dt>
                    <dd class="col-sm-8">{{ $counselor->training_level }}</dd>

                    <dt class="col-sm-4 text-muted">Registered</dt>
                    <dd class="col-sm-8">{{ $counselor->created_at->format('M d, Y') }}</dd>
                </dl>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header" style="background:#fff;">
                <span style="font-size:14px;">
                    <i class="fas fa-tags mr-2" ></i>Areas of Expertise
                </span>
            </div>
            <div class="card-body">
                @forelse($counselor->counselTypes as $type)
                    <span style="background:#e5f6f6; color:#0f5b5c; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:500; display:inline-block; margin:0 6px 6px 0;">
                        {{ $type->name }}
                    </span>
                @empty
                    <p class="text-muted mb-0" style="font-size:13px;">No expertise areas assigned.</p>
                @endforelse
            </div>
        </div>

       
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                <span style=" font-size:14px; flex:auto">
                    <i class="fas fa-calendar-alt mr-2"></i>Weekly Availability
                </span>
                <span style="font-size:12px;">
                    {{ $counselor->availabilities->count() }} slot{{ $counselor->availabilities->count() === 1 ? '' : 's' }} configured
                </span>
            </div>
            <div class="card-body">
                @php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    $grouped = $counselor->availabilities->groupBy('day');
                @endphp

                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:12px;">
                    @foreach($days as $day)
                        @php $slots = $grouped->get($day); @endphp
                        <div style="border:1px solid {{ $slots ? '#d7dcf2' : '#e8eaf2' }}; border-radius:10px; padding:14px; background:{{ $slots ? '#fff' : '#fafbfd' }};">
                            <div style="font-size:13px; font-weight:600; color:#1a1a2e; margin-bottom:10px; display:flex; align-items:center;">
                                <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:{{ $slots ? '#2e7d32' : '#d0d0d0' }}; margin-right:7px;"></span>
                                {{ $day }}
                            </div>

                            @if($slots && $slots->count())
                                <div style="display:flex; flex-direction:column; gap:6px;">
                                    @foreach($slots->sortBy('start_time') as $slot)
                                        <div style="background:#e5f6f6; color:#0f5b5c; font-size:12px; font-weight:500; padding:6px 10px; border-radius:7px; display:flex; align-items:center;">
                                            <i class="fas fa-clock" style="font-size:10px; margin-right:6px; color:#5c6bc0;"></i>
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                            <span class="mx-1">&ndash;</span>
                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div style="font-size:12px; color:#b0b0b8; font-style:italic;">
                                    <i class="fas fa-minus-circle" style="font-size:10px; margin-right:5px;"></i> Not available
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection