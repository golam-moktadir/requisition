@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('company.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Update
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   

        <div class="col-8">
            <form action="{{ route('company.update', ['id' => $single->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" 
                           value="{{ old('company_name', $single->company_name) }}" placeholder="Company Name" tabindex="1" autofocus>
                    @error('company_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone', $single->phone) }}" placeholder="Phone Number" tabindex="2" maxlength="11">
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $single->email) }}" placeholder="Email Address" tabindex="3">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="website" class="form-label">Website</label>
                    <input type="text" class="form-control" id="website" name="website" 
                           value="{{ old('website', $single->website) }}" placeholder="https://example.com" tabindex="4">
                    @error('website')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Company Logo</label>
                    <input type="file" class="form-control" id="image" name="image" tabindex="5">
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" 
                           value="{{ old('address', $single->address) }}" placeholder="Company Address" tabindex="6">
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" tabindex="7">
                        <option value="1" {{ old('status', $single->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ old('status', $single->status) == 2 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{route('company.index')}}" class="btn btn-info">Return back</a>
                </div>   
            </form>   
        </div>
    </div>
@endsection
@section('footerjs')
@endsection
