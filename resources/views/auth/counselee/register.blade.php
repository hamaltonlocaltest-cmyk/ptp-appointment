<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselee Registration</title>

    <!-- BS Stepper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper@1.7.0/dist/css/bs-stepper.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { background: url("{{ asset('images/bg-counselee.jpg') }}") no-repeat center center; background-size:cover; background-attachment:fixed; }
        .register-box { width:auto; padding:30px 0; }
        .card-header  { font-weight:600; }
        .section-title{ font-weight:700; border-bottom:2px solid #ddd; padding-bottom:6px; margin:20px 0 15px; }
        @media print { .btn,.card-header { display:none; } }
        .btn-login, .btn-primary { height:42px; border-radius:5px; background:#D30404; border-color:#D30404; color:#fff; font-size:18px; font-weight:600; transition:.3s; }
        .btn-secondary, .btn-success { height:42px; }
        .btn-login:hover, .btn-primary:hover, .btn-primary:active, .btn-primary:focus { background:#009643; color:#fff; }
        .logo-wrap  { background:#fff; padding:10px; border-radius:10px; display:inline-flex; }
        .login-logo { margin-bottom:20px; }
        .login-logo a { color:#fff; font-size:28px; font-weight:700; text-decoration:none; }
        .login-logo a span { color:#FFFFC4; }
        .btn-group-sm > .btn, .btn-sm { font-size:.875rem !important; height:auto !important; }
        .bs-stepper .step-trigger:focus { color:#00B24F; outline:0; }
        .active .bs-stepper-circle { background-color:#00B24F; }
        .required { color:red; }

        /* Validation states */
        .is-invalid { border-color:#dc3545 !important; }
        .invalid-feedback { display:block; color:#dc3545; font-size:.85em; margin-top:3px; }

        /* Mobile */
        @media (max-width:767.98px) {
            body { min-height:auto !important; }
            .bs-stepper-header { display:flex; align-items:stretch; }
            .bs-stepper-header .step { width:32%; margin-bottom:10px; }
            .bs-stepper-header .step-trigger { width:100%; justify-content:flex-start; padding:.75rem 1rem; }
            .bs-stepper-header .line { display:none; }
            .bs-stepper-label { white-space:normal; text-align:left; margin-left:10px; font-size:14px; }
            .bs-stepper-circle { flex-shrink:0; }
        }
    </style>
</head>

<body class="hold-transition">
<div class="container">
    <div class="row">
        <div class="col-12">

<div class="register-box">

    <div class="text-center">
        <div class="logo-wrap">
            <img src="{{ asset('images/persontoperson-logo.png') }}" class="img-fluid" alt="Person to Person Logo">
        </div>
    </div>

    <div class="login-logo text-center">
        <a href="#"><b>P2P</b> <span>Appointment</span></a>
    </div>

    <div class="card">
        <div class="card-body">

            <h3 class="text-center mb-4">Counselee Registration</h3>

            {{-- Server-side errors --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Success flash --}}
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form id="registrationForm"
                  action="{{ route('counselee.register') }}"
                  method="POST"
                  novalidate>
                @csrf

                <div class="bs-stepper" id="myStepper">

                    {{-- ── STEPPER HEADER ─────────────────────────────────── --}}
                    <div class="bs-stepper-header" role="tablist">

                        <div class="step" data-target="#register-part-one">
                            <button type="button" class="step-trigger" role="tab"
                                    aria-controls="register-part-one"
                                    id="register-part-one-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Personal Information</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#register-part-two">
                            <button type="button" class="step-trigger" role="tab"
                                    aria-controls="register-part-two"
                                    id="register-part-two-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Children / Medical / Seek Help</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#register-part-three">
                            <button type="button" class="step-trigger" role="tab"
                                    aria-controls="register-part-three"
                                    id="register-part-three-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Referral / Previous Counselling</span>
                            </button>
                        </div>

                    </div>{{-- /.bs-stepper-header --}}

                    {{-- ── STEPPER CONTENT ─────────────────────────────────── --}}
                    <div class="bs-stepper-content">

                        {{-- ===== STEP 1 : Personal Information ===== --}}
                        <div id="register-part-one" class="content"
                             role="tabpanel" aria-labelledby="register-part-one-trigger">

                            <div class="section-title">Personal Information</div>

                            <div class="row">
                                {{-- Title --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <select class="form-control" name="title">
                                            <option value="">Select</option>
                                            @foreach(['Mr','Miss','Mrs','Rev','Dr'] as $t)
                                            <option value="{{ $t }}" {{ old('title')==$t?'selected':'' }}>{{ $t }}.</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- First Name --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>First Name <span class="required">*</span></label>
                                        <input type="text" name="first_name" id="first_name"
                                               class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                               value="{{ old('first_name') }}">
                                        <div class="invalid-feedback" id="err-first_name">First name is required.</div>
                                    </div>
                                </div>
                                {{-- Last Name --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Last Name <span class="required">*</span></label>
                                        <input type="text" name="last_name" id="last_name"
                                               class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                               value="{{ old('last_name') }}">
                                        <div class="invalid-feedback" id="err-last_name">Last name is required.</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Telephone 1</label>
                                        <input type="text" class="form-control" name="telephone1"
                                               value="{{ old('telephone1') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Telephone 2</label>
                                        <input type="text" class="form-control" name="telephone2"
                                               value="{{ old('telephone2') }}">
                                    </div>
                                </div>
                                {{-- Email --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email <span class="required">*</span></label>
                                        <input type="email" class="form-control" name="email" id="email"
                                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               value="{{ old('email') }}">
                                        <div class="invalid-feedback" id="err-email">A valid email address is required.</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="number" class="form-control" name="age" min="1" max="120"
                                               value="{{ old('age') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Birth Date</label>
                                        <input type="date" class="form-control" name="birthdate"
                                               value="{{ old('birthdate') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="">Select</option>
                                            @foreach(['Male','Female','Other'] as $g)
                                            <option value="{{ $g }}" {{ old('gender')==$g?'selected':'' }}>{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Marital Status</label>
                                        <select class="form-control" name="marital_status">
                                            <option value="">Select</option>
                                            @foreach(['Single','Married','Divorced','Widowed'] as $ms)
                                            <option value="{{ $ms }}" {{ old('marital_status')==$ms?'selected':'' }}>{{ $ms }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="section-title mt-4">Account Details</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password <span class="required">*</span></label>
                                        <input type="password" class="form-control" name="password" id="password">
                                        <div class="invalid-feedback" id="err-password">Password must be at least 8 characters.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password <span class="required">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                                        <div class="invalid-feedback" id="err-password_confirmation">Passwords do not match.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" id="step1Next">Next</button>
                                </div>
                            </div>

                        </div>{{-- /#register-part-one --}}


                        {{-- ===== STEP 2 : Children / Medical / Seek Help ===== --}}
                        <div id="register-part-two" class="content"
                             role="tabpanel" aria-labelledby="register-part-two-trigger">

                            {{-- Children --}}
                            <div class="card card-outline card-primary mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Children</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" id="addChild">
                                            <i class="fas fa-plus"></i> Add Child
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                        <tr>
                                            <th width="40%">Name</th>
                                            <th width="20%">Gender</th>
                                            <th width="20%">Age</th>
                                            <th width="20%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="childrenTable">
                                        <tr>
                                            <td><input type="text" name="children[0][name]" class="form-control" placeholder="Child name"></td>
                                            <td>
                                                <select name="children[0][gender]" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </td>
                                            <td><input type="number" name="children[0][age]" class="form-control" min="0" max="30"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm removeChild"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Medical History --}}
                            <div class="card card-outline card-info mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Medical History</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" id="addMedical">
                                            <i class="fas fa-plus"></i> Add Medical History
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                        <tr>
                                            <th width="35%">Condition</th>
                                            <th>Details</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="medicalTable">
                                        <tr>
                                            <td><input type="text" class="form-control" name="medical_history[0][condition]" placeholder="Condition"></td>
                                            <td><input type="text" class="form-control" name="medical_history[0][details]" placeholder="Details"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm removeMedical"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Counselling Areas --}}
                            <div class="card card-outline card-danger mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Areas I Want to Seek Help / Counsel In</h3>
                                </div>
                                <div class="card-body">
                                    @php $areas = old('counselling_areas', []); @endphp
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach(['Premarital','Children','Study'] as $area)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="area_{{ Str::slug($area,'_') }}"
                                                       name="counselling_areas[]" value="{{ $area }}"
                                                       {{ in_array($area,$areas)?'checked':'' }}>
                                                <label class="custom-control-label" for="area_{{ Str::slug($area,'_') }}">{{ $area }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            @foreach(['Marital','Relationship','Self Improvement'] as $area)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="area_{{ Str::slug($area,'_') }}"
                                                       name="counselling_areas[]" value="{{ $area }}"
                                                       {{ in_array($area,$areas)?'checked':'' }}>
                                                <label class="custom-control-label" for="area_{{ Str::slug($area,'_') }}">{{ $area }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            @foreach(['Family','Work','Mental Health'] as $area)
                                            <div class="custom-control custom-checkbox mb-2">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="area_{{ Str::slug($area,'_') }}"
                                                       name="counselling_areas[]" value="{{ $area }}"
                                                       {{ in_array($area,$areas)?'checked':'' }}>
                                                <label class="custom-control-label" for="area_{{ Str::slug($area,'_') }}">{{ $area }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" id="step2Prev">Previous</button>
                                    <button type="button" class="btn btn-primary" id="step2Next">Next</button>
                                </div>
                            </div>

                        </div>{{-- /#register-part-two --}}


                        {{-- ===== STEP 3 : Referral / Previous Counselling ===== --}}
                        <div id="register-part-three" class="content"
                             role="tabpanel" aria-labelledby="register-part-three-trigger">

                            {{-- Referral --}}
                            <div class="card card-outline card-info mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Referral</h3>
                                </div>
                                <div class="card-body">
                                    @php $referral = old('referral'); @endphp
                                    @foreach(['Self','Friend','Relative','Pastor','PtP Website'] as $item)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input"
                                               id="ref_{{ Str::slug($item,'_') }}"
                                               name="referral" value="{{ $item }}"
                                               {{ $referral==$item?'checked':'' }}>
                                        <label class="custom-control-label" for="ref_{{ Str::slug($item,'_') }}">{{ $item }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Previous Counselling --}}
                            <div class="card card-outline card-warning mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Previous Counselling</h3>
                                </div>
                                <div class="card-body">
                                    <label>Have you received counselling previously?</label>
                                    <div class="mb-3">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input"
                                                   id="previous_yes" name="previous_counselling" value="Yes"
                                                   {{ old('previous_counselling')=='Yes'?'checked':'' }}>
                                            <label class="custom-control-label" for="previous_yes">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input"
                                                   id="previous_no" name="previous_counselling" value="No"
                                                   {{ old('previous_counselling')=='No'?'checked':'' }}>
                                            <label class="custom-control-label" for="previous_no">No</label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="prevDetailsWrapper" style="{{ old('previous_counselling')=='Yes' ? '' : 'display:none' }}">
                                        <label>If Yes, Please Provide Details</label>
                                        <textarea class="form-control" rows="4"
                                                  name="previous_counselling_details">{{ old('previous_counselling_details') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" id="step3Prev">Previous</button>
                                    <button type="submit" class="btn btn-login">
                                        <i class="fas fa-user-plus mr-1"></i> Submit Registration
                                    </button>
                                </div>
                            </div>

                        </div>{{-- /#register-part-three --}}

                    </div>{{-- /.bs-stepper-content --}}
                </div>{{-- /.bs-stepper --}}

            </form>

            <p class="text-center mt-4 mb-0">
                Already have an account?
                <a href="{{ route('counselee.login') }}">Login Here</a>
            </p>

        </div>
    </div>

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper@1.7.0/dist/js/bs-stepper.min.js"></script>

<script>
// ─────────────────────────────────────────────────────────────────────────────
// Stepper initialisation  (linear: true = must pass validation to advance)
// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

    window.stepper = new Stepper(document.querySelector('.bs-stepper'), {
        linear: true,
        animation: true
    });

    // Jump to the step that has server-side errors after redirect
    @if ($errors->any())
    // Step 1 errors
    @if ($errors->hasAny(['first_name','last_name','email','password','password_confirmation','title','address','telephone1','telephone2','age','birthdate','gender','marital_status']))
        stepper.to(1);
    @elseif ($errors->hasAny(['children','medical_history','counselling_areas']))
        stepper.to(2);
    @else
        stepper.to(3);
    @endif
    @endif

});

// ─────────────────────────────────────────────────────────────────────────────
// Helpers
// ─────────────────────────────────────────────────────────────────────────────
function setError(id, show) {
    const el  = document.getElementById(id);
    const err = document.getElementById('err-' + id);
    if (!el) return;
    if (show) {
        el.classList.add('is-invalid');
        if (err) err.style.display = 'block';
    } else {
        el.classList.remove('is-invalid');
        if (err) err.style.display = 'none';
    }
}

function clearErrors(ids) {
    ids.forEach(id => setError(id, false));
}

// Email format check
function isValidEmail(val) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim());
}

// ─────────────────────────────────────────────────────────────────────────────
// Step 1 validation
// ─────────────────────────────────────────────────────────────────────────────
document.getElementById('step1Next').addEventListener('click', function () {

    let valid = true;
    clearErrors(['first_name','last_name','email','password','password_confirmation']);

    const firstName = document.getElementById('first_name').value.trim();
    const lastName  = document.getElementById('last_name').value.trim();
    const email     = document.getElementById('email').value.trim();
    const password  = document.getElementById('password').value;
    const confirm   = document.getElementById('password_confirmation').value;

    if (!firstName) { setError('first_name', true); valid = false; }
    if (!lastName)  { setError('last_name',  true); valid = false; }

    if (!email || !isValidEmail(email)) {
        setError('email', true);
        document.getElementById('err-email').textContent = !email
            ? 'Email address is required.'
            : 'Please enter a valid email address.';
        valid = false;
    }

    if (!password || password.length < 8) {
        setError('password', true);
        document.getElementById('err-password').textContent = !password
            ? 'Password is required.'
            : 'Password must be at least 8 characters.';
        valid = false;
    }

    if (password && confirm !== password) {
        setError('password_confirmation', true);
        valid = false;
    }

    if (valid) {
        stepper.next();
    } else {
        // Scroll to first error
        const firstErr = document.querySelector('.is-invalid');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// ─────────────────────────────────────────────────────────────────────────────
// Step 2 : no required fields – just advance / go back
// ─────────────────────────────────────────────────────────────────────────────
document.getElementById('step2Next').addEventListener('click', function () {
    stepper.next();
});
document.getElementById('step2Prev').addEventListener('click', function () {
    stepper.previous();
});

// ─────────────────────────────────────────────────────────────────────────────
// Step 3 : back button
// ─────────────────────────────────────────────────────────────────────────────
document.getElementById('step3Prev').addEventListener('click', function () {
    stepper.previous();
});

// ─────────────────────────────────────────────────────────────────────────────
// Show / hide previous counselling details textarea
// ─────────────────────────────────────────────────────────────────────────────
document.querySelectorAll('[name="previous_counselling"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        document.getElementById('prevDetailsWrapper').style.display =
            this.value === 'Yes' ? '' : 'none';
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// Dynamic Children rows
// ─────────────────────────────────────────────────────────────────────────────
let childIndex = 1;

document.getElementById('addChild').addEventListener('click', function () {
    const row = `
    <tr>
        <td><input type="text" name="children[${childIndex}][name]" class="form-control" placeholder="Child name"></td>
        <td>
            <select name="children[${childIndex}][gender]" class="form-control">
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </td>
        <td><input type="number" name="children[${childIndex}][age]" class="form-control" min="0" max="30"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeChild"><i class="fas fa-trash"></i></button>
        </td>
    </tr>`;
    document.getElementById('childrenTable').insertAdjacentHTML('beforeend', row);
    childIndex++;
});

document.getElementById('childrenTable').addEventListener('click', function (e) {
    const btn = e.target.closest('.removeChild');
    if (btn) btn.closest('tr').remove();
});

// ─────────────────────────────────────────────────────────────────────────────
// Dynamic Medical History rows
// ─────────────────────────────────────────────────────────────────────────────
let medicalIndex = 1;

document.getElementById('addMedical').addEventListener('click', function () {
    const row = `
    <tr>
        <td><input type="text" class="form-control" name="medical_history[${medicalIndex}][condition]" placeholder="Condition"></td>
        <td><input type="text" class="form-control" name="medical_history[${medicalIndex}][details]" placeholder="Details"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeMedical"><i class="fas fa-trash"></i></button>
        </td>
    </tr>`;
    document.getElementById('medicalTable').insertAdjacentHTML('beforeend', row);
    medicalIndex++;
});

document.getElementById('medicalTable').addEventListener('click', function (e) {
    const btn = e.target.closest('.removeMedical');
    if (btn) btn.closest('tr').remove();
});

// ─────────────────────────────────────────────────────────────────────────────
// Clear inline error on input change
// ─────────────────────────────────────────────────────────────────────────────
['first_name','last_name','email','password','password_confirmation'].forEach(function (id) {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function () { setError(id, false); });
});
</script>
</body>
</html>