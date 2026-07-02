@extends('admin.layouts.app')
@section('title', 'Add Counselee')
@section('page-title', 'Add New Counselee')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.counselees.index') }}">Counselees</a></li>
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

        <form action="{{ route('admin.counselees.store') }}" method="POST" autocomplete="off">
            @csrf

            
            <div class="card mb-3">
                <div class="card-header" style="background:#fff;">
                    <span style=" font-weight:600;">
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
                                    <option value="{{ $t }}" {{ old('title')==$t?'selected':'' }}>{{ $t }}.</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}" placeholder="First name" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" placeholder="Last name" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Home address">{{ old('address') }}</textarea>
                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Telephone 1</label>
                                <input type="text" name="telephone1"
                                       class="form-control @error('telephone1') is-invalid @enderror"
                                       value="{{ old('telephone1') }}" placeholder="09XXXXXXXXX">
                                @error('telephone1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Telephone 2</label>
                                <input type="text" name="telephone2"
                                       class="form-control @error('telephone2') is-invalid @enderror"
                                       value="{{ old('telephone2') }}" placeholder="Optional">
                                @error('telephone2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="counselee@example.com" required>
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
                                       value="{{ old('birthdate') }}" required>
                                @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-3">
                                <label class="form-label">Age</label>
                                <input type="number" name="age" min="1" max="120"
                                       class="form-control @error('age') is-invalid @enderror"
                                       value="{{ old('age') }}" placeholder="Age">
                                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                    <option value="" disabled selected>Select</option>
                                    @foreach(['Male','Female','Other'] as $g)
                                    <option value="{{ $g }}" {{ old('gender')==$g?'selected':'' }}>{{ $g }}</option>
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
                                    <option value="{{ $ms }}" {{ old('marital_status')==$ms?'selected':'' }}>{{ $ms }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
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

                </div>
            </div>

           
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between" style="background:#fff;">
                    <span style="font-weight:600; flex:auto;">
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
                            <tr>
                                <td><input type="text" name="medical_history[0][condition]" class="form-control form-control-sm" placeholder="e.g. Hypertension"></td>
                                <td><input type="text" name="medical_history[0][details]" class="form-control form-control-sm" placeholder="Additional details"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeMedical"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
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
                    @php $selTypes = old('counsel_types', []); @endphp
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
                                   {{ old('referral')==$ref?'checked':'' }}>
                            <label class="custom-control-label" for="ref_{{ Str::slug($ref,'_') }}">{{ $ref }}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label font-weight-bold">Received Counselling Previously?</label><br>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="prev_yes"
                                   name="previous_counselling" value="Yes"
                                   {{ old('previous_counselling')=='Yes'?'checked':'' }}>
                            <label class="custom-control-label" for="prev_yes">Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="prev_no"
                                   name="previous_counselling" value="No"
                                   {{ old('previous_counselling')=='No'?'checked':'' }}>
                            <label class="custom-control-label" for="prev_no">No</label>
                        </div>
                    </div>

                    <div class="form-group" id="prevDetailsWrapper" style="{{ old('previous_counselling')=='Yes' ? '' : 'display:none' }}">
                        <label class="form-label">Previous Counselling Details</label>
                        <textarea name="previous_counselling_details" rows="3"
                                  class="form-control">{{ old('previous_counselling_details') }}</textarea>
                    </div>

                </div>
            </div>

           
            <div class="card mb-4">
                <div class="card-header" style="background:#fff;">
                    <span style="font-weight:600;">
                        <i class="fas fa-lock mr-2"></i> Login Credentials
                    </span>
                    <small class="text-muted ml-2">— Will be emailed to the counselee</small>
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

				<a href="{{ route('admin.counselees.index') }}"
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
    const el = document.getElementById(inputId);
    const ic = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.classList.toggle('fa-eye');
    ic.classList.toggle('fa-eye-slash');
}

// Previous counselling toggle
document.querySelectorAll('[name="previous_counselling"]').forEach(r => {
    r.addEventListener('change', function () {
        document.getElementById('prevDetailsWrapper').style.display = this.value === 'Yes' ? '' : 'none';
    });
});

// Dynamic children rows
let childIdx = 1;
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

// Dynamic medical rows
let medIdx = 1;
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