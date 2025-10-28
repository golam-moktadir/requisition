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
                    <label for="title" class="form-label">Company Name</label>
                    <select class="form-control" id="company_id" name="company_id" required tabindex="1" autofocus>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Title" tabindex="2" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required placeholder="Description" tabindex="2" rows="6" maxlength="1000">{{ old('description') }}</textarea>
                    @error('description') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required placeholder="Amount" tabindex="3" step="0.01">
                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="requested_to" class="form-label">Requested To</label>

                    <select class="form-control" id="requested_to" name="requested_to" value="{{ old('requested_to') }}" required tabindex="4">
                        <option value="">Select Requested To</option>
                        <option value="ceo">CEO</option>
                        <option value="managing_director">Managing Director</option>
                        <option value="manager">Manager</option>
                        <option value="accountant">Accountant</option>
                    </select>
                    @error('requested_to') <div class="text-danger">{{ $message }}</div> @enderror
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
