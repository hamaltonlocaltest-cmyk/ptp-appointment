@extends('admin.layouts.app')
@section('title', 'Add Counselee')
@section('page-title', 'Add New Counselee')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselees.index') }}">Counselees</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="color:#1a237e; font-size:15px; font-weight:600;">
                    <i class="fas fa-user-plus mr-2" style="color:#4a148c;"></i> Counselee Information
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

                <form action="{{ route('admin.counselees.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" placeholder="Enter last name" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="counselee@example.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" placeholder="09XXXXXXXXX" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" name="birthdate"
                                       class="form-control @error('birthdate') is-invalid @enderror"
                                       value="{{ old('birthdate') }}" required>
                                @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="male"   {{ old('gender')=='male'   ?'selected':'' }}>Male</option>
                                    <option value="female" {{ old('gender')=='female' ?'selected':'' }}>Female</option>
                                    <option value="other"  {{ old('gender')=='other'  ?'selected':'' }}>Other</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="active"   {{ old('status','active')=='active'   ?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ old('status')=='inactive' ?'selected':'' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr style="border-color:#f0f2f5; margin:20px 0;">

                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-lock mr-2" style="color:#9e9e9e;"></i>
                        <span style="font-size:13px; color:#666; font-weight:600;">Login Credentials</span>
                        <span class="ml-2" style="font-size:12px; color:#9e9e9e;">— Will be emailed to the counselee</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="pw1"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Minimum 8 characters" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-light border" onclick="togglePw('pw1','ic1')">
                                            <i id="ic1" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="pw2"
                                           class="form-control" placeholder="Re-enter password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-light border" onclick="togglePw('pw2','ic2')">
                                            <i id="ic2" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <a href="{{ route('admin.counselees.index') }}"
                           class="btn btn-light" style="border-radius:7px; padding:9px 22px; border:1px solid #e0e4ec; font-size:13px;">
                            <i class="fas fa-arrow-left mr-1"></i> Back
                        </a>
                        <button type="submit" class="btn"
                                style="background:#4a148c; color:#fff; border-radius:7px; padding:9px 26px; font-size:13px; font-weight:600;">
                            <i class="fas fa-save mr-1"></i> Save Counselee
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
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}
</script>
@endpush
