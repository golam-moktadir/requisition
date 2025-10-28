@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.all') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Create
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   

        <div class="col-8">
            <form action="{{ route('requisition.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Bank Name</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Bank Name" tabindex="1" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Account Holder Name</label>
                    <input type="text" class="form-control" id="title" name="account_holder_name" value="{{ old('title') }}" required placeholder="Bank Name" tabindex="1" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Account Number</label>
                    <input type="text" class="form-control" id="title" name="account_holder_name" value="{{ old('title') }}" required placeholder="Bank Name" tabindex="1" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Account Type</label>
                    <input type="text" class="form-control" id="title" name="account_holder_name" value="{{ old('title') }}" required placeholder="Bank Name" tabindex="1" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Branch Name</label>
                    <input type="text" class="form-control" id="title" name="account_holder_name" value="{{ old('title') }}" required placeholder="Bank Name" tabindex="1" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Branch Address</label>
                    <input type="text" class="form-control" id="title" name="account_holder_name" value="{{ old('title') }}" required placeholder="Bank Name" tabindex="1" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="transaction_mode" class="form-label">Transaction Mode</label>

                    <select class="form-control" id="transaction_mode" name="transaction_mode" value="{{ old('transaction_mode') }}" required tabindex="5">
                        <option value="">Select Transaction Mode</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="due">Due</option>
                    </select>
                    @error('transaction_mode') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="bank_check_info" class="form-label">Bank Check Info</label>

                    <select class="form-control" id="bank_check_info" name="bank_check_info" value="{{ old('bank_check_info') }}" tabindex="6">
                        <option value="">Select Bank Check Info</option>
                    </select>
                    @error('bank_check_info') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{route('requisition.all')}}" class="btn btn-info">Return back</a>
                </div>   
            </form>   
        </div>
    </div>
@endsection
@section('footerjs')
@endsection
