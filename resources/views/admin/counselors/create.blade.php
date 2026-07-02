@extends('admin.layouts.app')
@section('title', 'Add Counselor')
@section('page-title', 'Add New Counselor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselors.index') }}">Counselors</a></li>
    <li class="breadcrumb-item active">Add New</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">

        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form id="counselorForm" action="{{ route('admin.counselors.store') }}" method="POST" autocomplete="off">
            @csrf

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-user mr-2"></i> Basic Information
                    </span>
                </div>
                <div class="card-body p-4">

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
                                       value="{{ old('email') }}" placeholder="counselor@example.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" pattern="[0-9]{10}" maxlength="10"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" placeholder="10-digit number" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Full address" required>{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-briefcase mr-2"></i> Professional Details
                    </span>
                </div>
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                <input type="text" name="specialization"
                                       class="form-control @error('specialization') is-invalid @enderror"
                                       value="{{ old('specialization') }}"
                                       placeholder="e.g. Behavioral Therapy, Career Counseling" required>
                                @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                <input type="number" name="experience_years" min="0" max="60"
                                       class="form-control @error('experience_years') is-invalid @enderror"
                                       value="{{ old('experience_years') }}" required>
                                @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Areas of Expertise — dynamic from counsel_types --}}
                    <div class="form-group mb-2">
                        <label class="form-label">Areas of Expertise <span class="text-danger">*</span></label>
                        <div class="row">
                            @php $selTypes = old('counsel_types', []); @endphp
                            @forelse($counselTypes as $type)
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input"
                                           id="type_{{ $type->id }}"
                                           name="counsel_types[]" value="{{ $type->id }}"
                                           {{ in_array($type->id,$selTypes)?'checked':'' }}>
                                    <label class="custom-control-label" for="type_{{ $type->id }}">
                                        @if($type->icon)<i class="{{ $type->icon }} mr-1"></i>@endif
                                        {{ $type->name }}
                                    </label>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-muted">No counselling types have been configured yet.</div>
                            @endforelse
                        </div>
                        @error('counsel_types')<div class="text-danger" style="font-size:13px;">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-clock mr-2"></i> Availability
                    </span>
                    <small class="text-muted ml-2">— Click and drag on the grid to add hours</small>
                </div>
                <div class="card-body p-4">
                    @php $existingAvailability = old('availability', []); @endphp
                    @include('admin.counselors.availability-picker', ['existingAvailability' => $existingAvailability])
                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-sliders-h mr-2"></i> Service Preferences
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Mode of Counselling <span class="text-danger">*</span></label>
                                <select name="mode" class="form-control @error('mode') is-invalid @enderror" required>
                                    <option value="">Select</option>
                                    @foreach(['Online','In person','Both'] as $m)
                                    <option value="{{ $m }}" {{ old('mode')==$m?'selected':'' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                @error('mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Languages <span class="text-danger">*</span></label>
                                <input type="text" name="languages"
                                       class="form-control @error('languages') is-invalid @enderror"
                                       value="{{ old('languages') }}" placeholder="Comma separated" required>
                                @error('languages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-graduation-cap mr-2"></i> Training & Status
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">PtP Training Level <span class="text-danger">*</span></label>
                                <select name="training_level" class="form-control @error('training_level') is-invalid @enderror" required>
                                    <option value="">Select</option>
                                    @foreach(['Level 1','Level 2','Advanced','Certified','Other'] as $tl)
                                    <option value="{{ $tl }}" {{ old('training_level')==$tl?'selected':'' }}>{{ $tl }}</option>
                                    @endforeach
                                </select>
                                @error('training_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="pending"  {{ old('status','pending')=='pending'  ?'selected':'' }}>Pending</option>
                                    <option value="active"   {{ old('status')=='active'   ?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ old('status')=='inactive' ?'selected':'' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-lock mr-2" ></i> Login Credentials
                    </span>
                    <small class="text-muted ml-2">— Will be emailed to the counselor</small>
                </div>
                <div class="card-body p-4">
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
                </div>
            </div>
			
			
            <div class="action-buttons mb-4">

				<a href="{{ route('admin.counselors.index') }}"
				   class="btn btn-secondary action-btn mb-3 mb-sm-0">
					<i class="fas fa-arrow-left mr-1"></i> Back
				</a>

				<button type="submit" class="btn btn-primary action-btn mb-3 mb-sm-0">
					<i class="fas fa-save mr-1"></i> Save Counselee
				</button>

			</div>
           
        </form>
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

// Client-side sanity check before submit: at least one slot must exist
document.getElementById('counselorForm').addEventListener('submit', function (e) {
    if (typeof window.__availabilityHasAnySlot === 'function' && !window.__availabilityHasAnySlot()) {
        alert('Please add at least one time slot on the availability grid.');
        e.preventDefault();
    }
});
</script>
@endpush