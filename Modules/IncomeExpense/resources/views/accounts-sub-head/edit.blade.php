@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.accounts_head') }}">Account Sub Heads</a>
</li>
<li class="breadcrumb-item">
    Update
</li>
@endsection

@section('content-body')    
    
    <div class="mt-2 p-3 card">

        @include('admin.layouts.message')
        
        <div class="col-8">
            <form action="{{ route('income_expense.update_accounts_sub_head', $accountSubHeads->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="account_head_name" class="form-label">Account Head Name</label>
                    <input type="text" class="form-control" id="account_head_name" name="account_head_name" value="{{ old('account_head_name', $accountSubHeads->account_head_name) }}" required placeholder="Account Head Name" autofocus tabindex="1" maxlength="200">
                    @error('account_head_name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent Head</label>

                    <select class="form-control" id="parent_id" name="parent_id" required tabindex="2">
                        <option value="">Select Parent Head</option>
                        @foreach($account_heads AS $acc_id => $acc_head)
                            <option value="{{$acc_id}}"{{ old('parent_id', $accountSubHeads->parent_id)==$acc_id?' selected':'' }}>{{$acc_head}}</option>
                        @endforeach
                    </select>
                    @error('parent_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>

                    <select class="form-control" id="status" name="status" tabindex="3">
                        <option value="">Select Status</option>
                        <option value="1"{{ old('status', $accountSubHeads->status)=='1'?' selected':'' }}>Active</option>
                        <option value="2"{{ old('status', $accountSubHeads->status)=='2'?' selected':'' }}>InActive</option>
                    </select>
                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="" class="btn btn-warning">Reset</a>
                    <a href="{{route('income_expense.account_sub_head')}}" class="btn btn-info">Return back</a>
                </div> 
                
            </form>   
        </div>
    </div>
@endsection

@section('footerjs')

<script></script>
@endsection
