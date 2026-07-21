@extends('admin.layouts.app')
@section('title', 'Edit State')
@section('page-title', 'Edit State')
@section('breadcrumb')
    <li class="breadcrumb-item">Masters</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.masters.states.index') }}">States</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header" style="background:#fff;">
                <span style="font-weight:600;"><i class="fas fa-pen mr-2" style="color:#1a237e;"></i> Edit State</span>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 pl-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                <form action="{{ route('admin.masters.states.update', $state) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Country <span class="text-danger">*</span></label>
                                <select name="country_id" class="form-control" required>
                                    @foreach($countries as $c)
                                    <option value="{{ $c->id }}" {{ old('country_id', $state->country_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">State Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $state->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code', $state->code) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4" style="max-width:200px;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $state->status)=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $state->status)=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.masters.states.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update State</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
