@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.accounts_head') }}">Account Heads</a>
</li>
<li class="breadcrumb-item">
    Create New
</li>
@endsection

@section('content-body')    
    
    <div class="mt-2 p-3 card">

        @include('admin.layouts.message')
        
        <div class="col-8">
            <form action="{{ route('income_expense.store_accounts_head') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="account_head_name" class="form-label">Account Head Name</label>
                    <input type="text" class="form-control" id="account_head_name" name="account_head_name" value="{{ old('account_head_name') }}" required placeholder="Account Head Name" autofocus tabindex="1" maxlength="200">
                    @error('account_head_name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="head_category" class="form-label">Category</label>

                    <select class="form-control" id="head_category" name="head_category" value="{{ old('head_category') }}" required placeholder="Account Head Name" autofocus tabindex="2">
                        <option value="">Select Category</option>
                        <option value="1">Income</option>
                        <option value="2">Expense</option>
                    </select>
                    @error('head_category') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>

                    <select class="form-control" id="status" name="status" value="{{ old('status') }}" required placeholder="Account Head Name" autofocus tabindex="3">
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="2">InActive</option>
                    </select>
                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="" class="btn btn-warning">Reset</a>
                    <a href="{{route('income_expense.accounts_head')}}" class="btn btn-info">Return back</a>
                </div> 
                
            </form>   
        </div>
    </div>
@endsection

@section('footerjs')

<script></script>
@endsection
