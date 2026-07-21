@extends('admin.layouts.app')
@section('title', 'Edit Country')
@section('page-title', 'Edit Country')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.masters.countries.index') }}">Countries</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;"><i class="fas fa-pen mr-2" style="color:#1a237e;"></i> Edit Country</span>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('admin.masters.countries.update', $country) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Country Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $country->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">ISO Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" maxlength="2" class="form-control" style="text-transform:uppercase;" value="{{ old('code', $country->code) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone Code</label>
                                <input type="text" name="phone_code" class="form-control" value="{{ old('phone_code', $country->phone_code) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4" style="max-width:200px;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $country->status)=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $country->status)=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.masters.countries.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update Country</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
