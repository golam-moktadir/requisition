@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('bank.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Update
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-8">
            <form action="{{ route('bank.update', ['id' => $single->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="company_id" class="form-label">Company Name</label>
                    <select class="form-control" id="company_id" name="company_id" required tabindex="1" autofocus>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $single->company_id) == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="bank_name" class="form-label">Bank Name</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $single->bank_name) }}" placeholder="Bank Name" tabindex="1" autofocus>
                    @error('bank_name') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="account_holder_name" class="form-label">Account Holder Name</label>
                    <input type="text" class="form-control" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name', $single->account_holder_name) }}" placeholder="Account Holder Name" tabindex="2" autofocus>
                    @error('account_holder_name') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="account_no" class="form-label">Account Number</label>
                    <input type="text" class="form-control" id="account_no" name="account_no" value="{{ old('account_no', $single->account_no) }}" placeholder="Account Number" tabindex="3" autofocus>
                    @error('account_no') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="account_type" class="form-label">Account Type</label>
                    <select class="form-control" id="account_type" name="account_type" tabindex="4">
                        <option value="">Select Account Type</option>
                        <option value="current" {{ old('account_type', $single->account_type) == 'current' ? 'selected' : '' }}>Current</option>
                        <option value="savings" {{ old('account_type', $single->account_type) == 'savings' ? 'selected' : '' }}>Savings</option>
                        <option value="fdr" {{ old('account_type', $single->account_type) == 'fdr' ? 'selected' : '' }}>FDR</option>
                        <option value="cc" {{ old('account_type', $single->account_type) == 'cc' ? 'selected' : '' }}>CC</option>
                    </select>

                    @error('account_type') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="branch_name" class="form-label">Branch Name</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ old('branch_name', $single->branch_name) }}" placeholder="Branch Name" tabindex="5" autofocus>
                    @error('branch_name') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="branch_address" class="form-label">Branch Address</label>
                    <input type="text" class="form-control" id="branch_address" name="branch_address" value="{{ old('branch_address', $single->branch_address) }}" placeholder="Branch Address" tabindex="6" autofocus>
                    @error('branch_address') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{route('bank.index')}}" class="btn btn-info">Return back</a>
                </div>   
            </form>   
        </div>
    </div>
@endsection
@section('footerjs')
@endsection
