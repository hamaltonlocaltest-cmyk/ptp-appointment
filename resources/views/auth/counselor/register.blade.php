<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselor — Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
	
	<style>
		.register-page { /*background: #1b5e20;*/ background: url("{{ asset('images/bg-counselor-login.webp') }}") no-repeat center center; background-size:cover }
		.login-wrapper{min-height:100vh; display:flex; justify-content:center; align-items:center; position:relative; overflow:hidden; padding:30px; min-width: 520px;}
		.logo-wrap img {max-width: 220px;}
		.logo-wrap {text-align: center;margin-bottom: 20px;}
		.circle{position:absolute;   border-radius:50%;  background:rgba(255,255,255,.06);}
		.circle1{width: 200px;height: 200px; top: 0; left: -60px;}
		.circle2{ width:180px;  height:180px;   right:0; bottom:-60px;}
		.login-card{width:100%;  max-width:470px; background:rgba(255,255,255,.95); border-radius:28px; padding:30px 40px; box-shadow:0 30px 60px rgba(0,0,0,.18); position:relative; z-index:10;}
		.logo-icon{width:80px;  height:80px;  margin:auto;   border-radius:50%;    background:#edf8f1;    display:flex;    justify-content:center;    align-items:center;    font-size:32px;    color:#00B24F;    margin-bottom:25px;}
		.login-card h1{ font-size:20px; font-weight:700; text-align:center; color:#12223b;}
		.login-card h1 span{  color:#00B24F;  font-weight:300;}
		.login-card h1 {  display: flex;   align-items: center;    justify-content: center;    gap: 12px;    font-size: 20px; font-weight: 700; color: #12223b; margin-bottom: 20px;}
		.login-card h1::before, .login-card h1::after { content: ""; flex: 1; height: 2px; background: linear-gradient(to right, transparent, #d5dde8);}
		.login-card h1::after {background: linear-gradient(to left, transparent, #d5dde8);}
		.login-card h3{ margin-top: 10px;text-align: center; font-weight: 700;color: #12223b; font-size: 20px; margin-bottom: 0;}
		.subtitle{ text-align:center;   color:#6c757d; margin-bottom:15px;}
		.form-group label{font-size:13px;  text-transform:uppercase; letter-spacing:1px; color:#5f6b7a; font-weight:600;}
		.input-group-text{ background:#fff;  /*border-right:none;*/   color:#00B24F;}
		.input-group-append .input-group-text{border-left:none;}
		.input-group .form-control {border-right: 1px solid #ced4da;}
		.form-control:focus{box-shadow:none; border-color:#ced4da;}
		.form-control {height: 38px;}	
		.form-group {margin-bottom: 8px;}
		.btn-login{height: 42px;border-radius: 5px; background:#D30404; color:#fff; font-size:18px; font-weight:600;transition:.3s;}
		.btn-login:hover{ background:#009643; color:#fff;}
		.divider{margin:15px 0; text-align:center; position:relative;}
		.divider:before{ content:""; position:absolute; top:50%; left:0; width:100%; height:1px; background:#ddd;}
		.divider span{position:relative;background:#fff; padding:0 15px; color:#777;}
		.create-account{display:block; text-align:center; color:#00B24F; font-size:18px; font-weight:600;text-decoration:none;}
		.bottom-links{margin-top:15px; text-align:center;font-size:14px;}
		.bottom-links span{ margin:0 15px; color:#bbb;}
		.bottom-links a{color:#0d6efd; text-decoration:none;}
		.input-group .form-control.is-invalid {  border-left: 0;  border-right: 1px solid #f00 !important;}
		.alert-modern-danger{    display:flex;    align-items:center;    gap:10px;    padding:10px 14px;    margin-bottom:20px;    border:none;    border-left:4px solid #dc3545;    border-radius:10px;    background:#fff5f5;    color:#c62828;    font-size:.92rem;    font-weight:500;    box-shadow:0 4px 12px rgba(220,53,69,.08);}
		.alert-modern-danger i{    color:#dc3545;    font-size:1rem;    flex-shrink:0;}
		.alert-modern-success{    display:flex;    align-items:center;    gap:10px;    padding:10px 14px;    margin-bottom:20px;    border:none;    border-left:4px solid #00B24F;    border-radius:10px;    background:#f2fcf5;
		color:#198754;    font-size:.92rem;    font-weight:500;    box-shadow:0 4px 12px rgba(0,178,79,.08);}
		.alert-modern-success i{color:#00B24F; font-size:1rem;    flex-shrink:0;}
		.input-group .form-control:focus{ border-right: 1px solid #00B24F;}
		
		@media(max-width:576px){
			.login-card{ padding:30px 22px;}
			.login-wrapper{min-width: 320px;}
			.logo-icon{width:70px;height:70px;font-size:28px;}

		}
	</style>
	
</head>
<body class="hold-transition register-page">

<div class="login-wrapper">

    <!-- Decorative Shapes -->

    <span class="circle circle1"></span>
    <span class="circle circle2"></span>

    <div class="login-card">

        <div class="logo-wrap"><img src="{{ asset('images/persontoperson-logo.png') }}" class="img-fluid" alt="Person to Person Logo"></div>

        <h1>P2P <span>Appointment</span></h1>

        <h3>Counselor Registration</h3>

        <p class="subtitle">
            Create your counselor account
        </p>
		
		@if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

			
			<form action="{{ route('counselor.register') }}" method="POST">
				@csrf

				<div class="row">

					<!-- First Name -->
					<div class="col-md-6">
						<div class="form-group">
							<div class="input-group">

								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="far fa-user"></i>
									</span>
								</div>

								<input type="text"
									   name="first_name"
									   class="form-control @error('first_name') is-invalid @enderror"
									   placeholder="First Name"
									   value="{{ old('first_name') }}"
									   required>

							</div>
						</div>
					</div>

					<!-- Last Name -->
					<div class="col-md-6">
						<div class="form-group">
							<div class="input-group">

								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="far fa-user"></i>
									</span>
								</div>

								<input type="text"
									   name="last_name"
									   class="form-control @error('last_name') is-invalid @enderror"
									   placeholder="Last Name"
									   value="{{ old('last_name') }}"
									   required>

							</div>
						</div>
					</div>

				</div>

				<!-- Email -->
				<div class="form-group">
					<div class="input-group">

						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="far fa-envelope"></i>
							</span>
						</div>

						<input type="email"
							   name="email"
							   class="form-control @error('email') is-invalid @enderror"
							   placeholder="Email Address"
							   value="{{ old('email') }}"
							   required>

					</div>
				</div>

				<!-- Phone -->
				<div class="form-group">
					<div class="input-group">

						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fas fa-phone"></i>
							</span>
						</div>

						<input type="text"
							   name="phone"
							   class="form-control @error('phone') is-invalid @enderror"
							   placeholder="Phone Number"
							   value="{{ old('phone') }}"
							   required>

					</div>
				</div>

				<!-- Specialization -->
				<div class="form-group">
					<div class="input-group">

						<div class="input-group-prepend">
							<span class="input-group-text">
								<i class="fas fa-briefcase-medical"></i>
							</span>
						</div>

						<input type="text"
							   name="specialization"
							   class="form-control @error('specialization') is-invalid @enderror"
							   placeholder="Specialization"
							   value="{{ old('specialization') }}"
							   required>

							</div>
						</div>

						<!-- Country / State / City -->
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-globe-asia"></i></span>
										</div>
										<select name="country_id" id="countrySelect"
												class="form-control @error('country_id') is-invalid @enderror" required>
											<option value="">Country</option>
											@foreach($countries as $country)
											<option value="{{ $country->id }}" {{ old('country_id', $defaultCountry?->id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
										</div>
										<select name="state_id" id="stateSelect"
												class="form-control @error('state_id') is-invalid @enderror" required>
											<option value="">State</option>
											@foreach($states as $state)
											<option value="{{ $state->id }}" {{ old('state_id', $defaultState?->id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-city"></i></span>
										</div>
										<select name="city_id" id="citySelect"
												class="form-control @error('city_id') is-invalid @enderror" required>
											<option value="">City</option>
											@foreach($cities as $city)
											<option value="{{ $city->id }}" {{ old('city_id', $defaultCity?->id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>

						<!-- Password -->
						<div class="form-group">
							<div class="input-group">

								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-lock"></i>
									</span>
								</div>

								<input type="password"
									   name="password"
									   class="form-control @error('password') is-invalid @enderror"
									   placeholder="Password"
									   required>

								<div class="input-group-append">
									<span class="input-group-text">
										<i class="far fa-eye"></i>
									</span>
								</div>

							</div>
						</div>

						<!-- Confirm Password -->
						<div class="form-group">
							<div class="input-group">

								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fas fa-lock"></i>
									</span>
								</div>

								<input type="password"
									   name="password_confirmation"
									   class="form-control"
									   placeholder="Confirm Password"
									   required>

								<div class="input-group-append">
									<span class="input-group-text">
										<i class="far fa-eye"></i>
									</span>
								</div>

							</div>
						</div>

						<!-- Register Button -->
						<button type="submit" class="btn btn-login btn-block">

							<i class="fas fa-user-plus mr-2"></i>

							Create Counselor Account

						</button>

					</form>
			

        <div class="divider">
            <span>OR</span>
        </div>
		

        <div class="bottom-links">

            <a href="{{ route('admin.login') }}">Admin Login</a>

            <span>|</span>

            <a href="{{ route('counselee.login') }}">Counselee Login</a>

        </div>

    </div>

</div>



<div class="register-box d-none">
    <div class="register-logo">
        <a href="#"><b>P2P</b> <span>Appointment</span></a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <div class="role-badge"><span><i class="fas fa-user-md"></i> Counselor Registration</span></div>
            <p class="login-box-msg text-muted">Create your counselor account</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('counselor.register') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name"
                                   value="{{ old('first_name') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                                   value="{{ old('last_name') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email address"
                           value="{{ old('email') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="phone" class="form-control" placeholder="Phone Number"
                           value="{{ old('phone') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" name="specialization" class="form-control" placeholder="Specialization"
                           value="{{ old('specialization') }}" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-briefcase-medical"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success-custom btn-block">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </button>
                    </div>
                </div>
            </form>

            <p class="mb-0 mt-3 text-center">
                Already have an account? <a href="{{ route('counselor.login') }}" style="color:#1b5e20;">Sign in</a>
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
document.getElementById('countrySelect').addEventListener('change', function () {
    const stateSelect = document.getElementById('stateSelect');
    const citySelect  = document.getElementById('citySelect');
    stateSelect.innerHTML = '<option value="">State</option>';
    citySelect.innerHTML  = '<option value="">City</option>';
    if (!this.value) return;
    fetch(`/locations/states/${this.value}`)
        .then(r => r.json())
        .then(data => {
            data.states.forEach(s => stateSelect.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.name}</option>`));
        });
});

document.getElementById('stateSelect').addEventListener('change', function () {
    const citySelect = document.getElementById('citySelect');
    citySelect.innerHTML = '<option value="">City</option>';
    if (!this.value) return;
    fetch(`/locations/cities/${this.value}`)
        .then(r => r.json())
        .then(data => {
            data.cities.forEach(c => citySelect.insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`));
        });
});
</script>
</body>
</html>
