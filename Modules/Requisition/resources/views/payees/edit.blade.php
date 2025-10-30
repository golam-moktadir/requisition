@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('payee.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Update
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   

        <div class="col-8">
            <form action="{{ route('payee.update', ['id' => $single->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="payee_name" class="form-label">Payee Name</label>
                    <input type="text" class="form-control" id="payee_name" name="payee_name" 
                           value="{{ old('payee_name', $single->payee_name) }}" placeholder="Payee Name" tabindex="1" autofocus>
                    @error('payee_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="account_holder_name" class="form-label">Account Holder Name</label>
                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" 
                           value="{{ old('account_holder_name', $single->account_holder_name) }}" placeholder="Account Holder Name" tabindex="2">
                    @error('account_holder_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="account_number" class="form-label">Account Number</label>
                    <input type="account_number" class="form-control" id="account_number" name="account_number" 
                           value="{{ old('account_number', $single->account_number) }}" placeholder="Account Number" tabindex="3">
                    @error('account_number')
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
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" 
                           value="{{ old('address', $single->address) }}" placeholder="Payee Address" tabindex="6">
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{route('payee.index')}}" class="btn btn-info">Return back</a>
                </div>   
            </form>   
        </div>
    </div>
@endsection
@section('footerjs')
@endsection
