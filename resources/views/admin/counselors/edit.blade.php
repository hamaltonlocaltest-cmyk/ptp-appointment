@extends('admin.layouts.app')
@section('title', 'Edit Counselor')
@section('page-title', 'Edit Counselor')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselors.index') }}">Counselors</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">

        
		
		<div class="edit-header mb-4">

			<div class="d-flex align-items-center">

				<div class="edit-icon">
					<i class="bi bi-pencil-square"></i>
				</div>

				<div class="ml-3">
					<div class="edit-label">Currently Editing</div>
					<div class="edit-name">{{ $counselor->full_name }}</div>
				</div>

			</div>

			 @if($counselor->status === 'active')
				<span class="badge-active">
					<i class="bi bi-check-circle-fill mr-1"></i>
					Active
				</span>
			@elseif($counselor->status === 'pending')
				<span class="badge" style="background:#fff3cd; color:#856404; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600;">
					<i class="bi bi-x-circle-fill mr-1"></i>
					Pending
				</span>
			@else
				<span class="badge-inactive">
					<i class="bi bi-x-circle-fill mr-1"></i>
					Inactive
				</span>
			@endif

		</div>
		

        @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form id="counselorForm" action="{{ route('admin.counselors.update', $counselor) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

           
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
                                       value="{{ old('first_name', $counselor->first_name) }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $counselor->last_name) }}" required>
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
                                       value="{{ old('email', $counselor->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" pattern="[0-9]{10}" maxlength="10"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $counselor->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  required>{{ old('address', $counselor->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
            </div>

          
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-briefcase mr-2" ></i> Professional Details
                    </span>
                </div>
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                <input type="text" name="specialization"
                                       class="form-control @error('specialization') is-invalid @enderror"
                                       value="{{ old('specialization', $counselor->specialization) }}" required>
                                @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                <input type="number" name="experience_years" min="0" max="60"
                                       class="form-control @error('experience_years') is-invalid @enderror"
                                       value="{{ old('experience_years', $counselor->experience_years) }}" required>
                                @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Areas of Expertise — dynamic from counsel_types --}}
                    <div class="form-group mb-2">
                        <label class="form-label">Areas of Expertise <span class="text-danger">*</span></label>
                        <div class="row">
                            @php
                                $selTypes = old('counsel_types', $counselor->counselTypes->pluck('id')->toArray());
                            @endphp
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
                    @php
                        $existingAvailability = old('availability')
                            ?? $counselor->availabilities->map(fn($a) => [
                                'day' => $a->day,
                                'start_time' => substr($a->start_time, 0, 5),
                                'end_time' => substr($a->end_time, 0, 5),
                            ])->toArray();
                    @endphp
                    @include('admin.counselors.availability-picker', ['existingAvailability' => $existingAvailability])
                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-sliders-h mr-2" ></i> Service Preferences
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Mode of Counselling <span class="text-danger">*</span></label>
                                <select name="mode" class="form-control @error('mode') is-invalid @enderror" required>
                                    @foreach(['Online','In person','Both'] as $m)
                                    <option value="{{ $m }}" {{ old('mode',$counselor->mode)==$m?'selected':'' }}>{{ $m }}</option>
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
                                       value="{{ old('languages', $counselor->languages) }}" required>
                                @error('languages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-graduation-cap mr-2" ></i> Training & Status
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">PtP Training Level <span class="text-danger">*</span></label>
                                <select name="training_level" class="form-control @error('training_level') is-invalid @enderror" required>
                                    @foreach(['Level 1','Level 2','Advanced','Certified','Other'] as $tl)
                                    <option value="{{ $tl }}" {{ old('training_level',$counselor->training_level)==$tl?'selected':'' }}>{{ $tl }}</option>
                                    @endforeach
                                </select>
                                @error('training_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="pending"  {{ old('status',$counselor->status)=='pending'  ?'selected':'' }}>Pending</option>
                                    <option value="active"   {{ old('status',$counselor->status)=='active'   ?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ old('status',$counselor->status)=='inactive' ?'selected':'' }}>Inactive</option>
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
                        <i class="fas fa-lock mr-2" ></i> Change Password
                    </span>
                    <small class="text-muted ml-2">— Leave blank to keep current password</small>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="pw1"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Leave blank to keep current">
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
                                <label class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="pw2"
                                           class="form-control" placeholder="Re-enter new password">
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
				   class="btn btn-secondary action-btn">
					<i class="fas fa-arrow-left mr-1"></i> Back
				</a>

				<div class="action-group">

					<button type="submit" class="btn btn-primary action-btn">
						<i class="fas fa-save mr-1"></i> Update Counselor
					</button>

					<form action="{{ route('admin.counselors.destroy', $counselor) }}" method="POST"
                  onsubmit="return confirm('Delete {{ $counselor->full_name }}? This cannot be undone.')">

						@csrf
						@method('DELETE')

						<button type="submit" class="btn btn-danger action-btn">
							<i class="fas fa-trash mr-1"></i> Delete Counselor
						</button>

					</form>

				</div>

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