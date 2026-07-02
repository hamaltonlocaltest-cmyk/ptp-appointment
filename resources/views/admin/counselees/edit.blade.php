@extends('admin.layouts.app')
@section('title', 'Edit Counselee')
@section('page-title', 'Edit Counselee')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselees.index') }}">Counselees</a></li>
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
					<div class="edit-name">{{ $counselee->full_name }}</div>
				</div>

			</div>

			@if($counselee->status === 'active')
				<span class="status-pill status-active">
					<i class="bi bi-check-circle-fill mr-1"></i>
					Active
				</span>
			@else
				<span class="status-pill status-inactive">
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

        <form id="editForm" action="{{ route('admin.counselees.update', $counselee) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-user mr-2"></i> Personal Information
                    </span>
                </div>
                <div class="card-body p-4">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label class="form-label">Title</label>
                                <select name="title" class="form-control">
                                    <option value="">—</option>
                                    @foreach(['Mr','Miss','Mrs','Rev','Dr'] as $t)
                                    <option value="{{ $t }}" {{ old('title',$counselee->title)==$t?'selected':'' }}>{{ $t }}.</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $counselee->first_name) }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $counselee->last_name) }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Home address">{{ old('address', $counselee->address) }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Telephone 1</label>
                                <input type="text" name="telephone1"
                                       class="form-control @error('telephone1') is-invalid @enderror"
                                       value="{{ old('telephone1', $counselee->telephone1) }}">
                                @error('telephone1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Telephone 2</label>
                                <input type="text" name="telephone2"
                                       class="form-control @error('telephone2') is-invalid @enderror"
                                       value="{{ old('telephone2', $counselee->telephone2) }}">
                                @error('telephone2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $counselee->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                                <input type="date" name="birthdate"
                                       class="form-control @error('birthdate') is-invalid @enderror"
                                       value="{{ old('birthdate', $counselee->birthdate?->format('Y-m-d')) }}" required>
                                @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" min="1" max="120"
                                       class="form-control @error('age') is-invalid @enderror"
                                       value="{{ old('age', $counselee->age) }}">
                                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    @foreach(['Male','Female','Other'] as $g)
                                    <option value="{{ $g }}" {{ old('gender',$counselee->gender)==$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status" class="form-control">
                                    <option value="">Select</option>
                                    @foreach(['Single','Married','Divorced','Widowed'] as $ms)
                                    <option value="{{ $ms }}" {{ old('marital_status',$counselee->marital_status)==$ms?'selected':'' }}>{{ $ms }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="active"   {{ old('status',$counselee->status)=='active'   ?'selected':'' }}>Active</option>
                                    <option value="inactive" {{ old('status',$counselee->status)=='inactive' ?'selected':'' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                    <span style="font-weight:600; flex: auto;">
                        <i class="fas fa-child mr-2"></i> Children
                    </span>
                    <button type="button" class="btn btn-sm btn-primary" id="addChild">
                        <i class="fas fa-plus mr-1"></i> Add Child
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead style="background:#f8f9fc;">
                            <tr>
                                <th>Name</th>
                                <th width="160">Gender</th>
                                <th width="120">Age</th>
                                <th width="60" class="text-center">Del</th>
                            </tr>
                        </thead>
                        <tbody id="childrenTable">
                            @forelse($counselee->children as $i => $child)
                            <tr>
                                <td><input type="text" name="children[{{ $i }}][name]" class="form-control form-control-sm" value="{{ $child->name }}" placeholder="Child name"></td>
                                <td>
                                    <select name="children[{{ $i }}][gender]" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <option value="Male"   {{ $child->gender=='Male'?'selected':'' }}>Male</option>
                                        <option value="Female" {{ $child->gender=='Female'?'selected':'' }}>Female</option>
                                    </select>
                                </td>
                                <td><input type="number" name="children[{{ $i }}][age]" class="form-control form-control-sm" min="0" max="30" value="{{ $child->age }}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeChild"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td><input type="text" name="children[0][name]" class="form-control form-control-sm" placeholder="Child name"></td>
                                <td>
                                    <select name="children[0][gender]" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </td>
                                <td><input type="number" name="children[0][age]" class="form-control form-control-sm" min="0" max="30" placeholder="Age"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeChild"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                    <span style="font-weight:600; flex:auto;">
                        <i class="fas fa-notes-medical mr-2"></i> Medical History
                    </span>
                    <button type="button" class="btn btn-sm btn-primary" id="addMedical">
                        <i class="fas fa-plus mr-1"></i> Add Entry
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead style="background:#f8f9fc;">
                            <tr>
                                <th width="35%">Condition</th>
                                <th>Details</th>
                                <th width="60" class="text-center">Del</th>
                            </tr>
                        </thead>
                        <tbody id="medicalTable">
                            @forelse($counselee->medicalHistories as $i => $hist)
                            <tr>
                                <td><input type="text" name="medical_history[{{ $i }}][condition]" class="form-control form-control-sm" value="{{ $hist->condition }}"></td>
                                <td><input type="text" name="medical_history[{{ $i }}][details]" class="form-control form-control-sm" value="{{ $hist->details }}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeMedical"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td><input type="text" name="medical_history[0][condition]" class="form-control form-control-sm" placeholder="e.g. Hypertension"></td>
                                <td><input type="text" name="medical_history[0][details]" class="form-control form-control-sm" placeholder="Additional details"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeMedical"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-hand-holding-heart mr-2"></i> Areas Seeking Counselling
                    </span>
                </div>
                <div class="card-body">
                    @php
                        $selTypes = old('counsel_types', $counselee->counselTypes->pluck('id')->toArray());
                    @endphp
                    <div class="row">
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
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-share-alt mr-2"></i> Referral & Previous Counselling
                    </span>
                </div>
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label class="form-label font-weight-bold">Referred By</label><br>
                        @foreach(['Self','Friend','Relative','Pastor','PtP Website'] as $ref)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input"
                                   id="ref_{{ Str::slug($ref,'_') }}"
                                   name="referral" value="{{ $ref }}"
                                   {{ old('referral',$counselee->referral)==$ref?'checked':'' }}>
                            <label class="custom-control-label" for="ref_{{ Str::slug($ref,'_') }}">{{ $ref }}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Received Counselling Previously?</label><br>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="prev_yes"
                                   name="previous_counselling" value="Yes"
                                   {{ old('previous_counselling',$counselee->previous_counselling)=='Yes'?'checked':'' }}>
                            <label class="custom-control-label" for="prev_yes">Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="prev_no"
                                   name="previous_counselling" value="No"
                                   {{ old('previous_counselling',$counselee->previous_counselling)=='No'?'checked':'' }}>
                            <label class="custom-control-label" for="prev_no">No</label>
                        </div>
                    </div>

                    <div class="form-group" id="prevDetailsWrapper"
                         style="{{ old('previous_counselling',$counselee->previous_counselling)=='Yes' ? '' : 'display:none' }}">
                        <label class="form-label">Previous Counselling Details</label>
                        <textarea name="previous_counselling_details" rows="3"
                                  class="form-control">{{ old('previous_counselling_details', $counselee->previous_counselling_details) }}</textarea>
                    </div>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-lock mr-2"></i> Change Password
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

				<a href="{{ route('admin.counselees.index') }}"
				   class="btn btn-secondary action-btn">
					<i class="fas fa-arrow-left mr-1"></i> Back
				</a>

				<div class="action-group">

					<button type="submit" class="btn btn-primary action-btn">
						<i class="fas fa-save mr-1"></i> Update Counselee
					</button>

					<form action="{{ route('admin.counselees.destroy', $counselee) }}"
						  method="POST"
						  onsubmit="return confirmDelete('{{ $counselee->full_name }}')">

						@csrf
						@method('DELETE')

						<button type="submit" class="btn btn-danger action-btn">
							<i class="fas fa-trash mr-1"></i> Delete Counselee
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
    const el = document.getElementById(inputId);
    const ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.classList.toggle('fa-eye');
    ic.classList.toggle('fa-eye-slash');
}
function confirmDelete(name) {
    return confirm('Delete "' + name + '"?\nThis cannot be undone.');
}

document.querySelectorAll('[name="previous_counselling"]').forEach(r => {
    r.addEventListener('change', function () {
        document.getElementById('prevDetailsWrapper').style.display = this.value === 'Yes' ? '' : 'none';
    });
});

let childIdx = {{ max($counselee->children->count(), 1) }};
document.getElementById('addChild').addEventListener('click', function () {
    document.getElementById('childrenTable').insertAdjacentHTML('beforeend', `
    <tr>
        <td><input type="text" name="children[${childIdx}][name]" class="form-control form-control-sm" placeholder="Child name"></td>
        <td><select name="children[${childIdx}][gender]" class="form-control form-control-sm">
            <option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option>
        </select></td>
        <td><input type="number" name="children[${childIdx}][age]" class="form-control form-control-sm" min="0" max="30" placeholder="Age"></td>
        <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeChild"><i class="fas fa-trash"></i></button></td>
    </tr>`);
    childIdx++;
});
document.getElementById('childrenTable').addEventListener('click', e => {
    if (e.target.closest('.removeChild')) e.target.closest('tr').remove();
});

let medIdx = {{ max($counselee->medicalHistories->count(), 1) }};
document.getElementById('addMedical').addEventListener('click', function () {
    document.getElementById('medicalTable').insertAdjacentHTML('beforeend', `
    <tr>
        <td><input type="text" name="medical_history[${medIdx}][condition]" class="form-control form-control-sm" placeholder="e.g. Hypertension"></td>
        <td><input type="text" name="medical_history[${medIdx}][details]" class="form-control form-control-sm" placeholder="Additional details"></td>
        <td class="text-center"><button type="button" class="btn btn-danger btn-sm removeMedical"><i class="fas fa-trash"></i></button></td>
    </tr>`);
    medIdx++;
});
document.getElementById('medicalTable').addEventListener('click', e => {
    if (e.target.closest('.removeMedical')) e.target.closest('tr').remove();
});
</script>
@endpush