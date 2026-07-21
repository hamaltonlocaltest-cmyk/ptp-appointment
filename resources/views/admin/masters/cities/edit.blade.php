@extends('admin.layouts.app')
@section('title', 'Edit City')
@section('page-title', 'Edit City')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.masters.cities.index') }}">Cities</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;"><i class="fas fa-pen mr-2" style="color:#1a237e;"></i> Edit City</span>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('admin.masters.cities.update', $city) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select name="country_id" id="countrySelect" class="form-control" required>
                                    @foreach($countries as $c)
                                    <option value="{{ $c->id }}" {{ old('country_id', $city->country_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <select name="state_id" id="stateSelect" class="form-control" required>
                                    @foreach($states as $s)
                                    <option value="{{ $s->id }}" {{ old('state_id', $city->state_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">City Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $city->name) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4" style="max-width:200px;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $city->status)=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $city->status)=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.masters.cities.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update City</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const countrySelect = document.getElementById('countrySelect');
const stateSelect   = document.getElementById('stateSelect');

countrySelect.addEventListener('change', function () {
    const countryId = this.value;
    stateSelect.innerHTML = '<option value="">Loading...</option>';

    if (!countryId) {
        stateSelect.innerHTML = '<option value="">Select Country First</option>';
        return;
    }

    fetch('{{ url("locations/states") }}/' + countryId)
        .then(r => r.json())
        .then(data => {
            stateSelect.innerHTML = '<option value="">Select State</option>';
            data.states.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                stateSelect.appendChild(opt);
            });
        })
        .catch(() => { stateSelect.innerHTML = '<option value="">Could not load states</option>'; });
});
</script>
@endpush
