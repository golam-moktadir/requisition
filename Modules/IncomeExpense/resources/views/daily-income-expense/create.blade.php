@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.daily_transactions') }}">Daily Transactions</a>
</li>
<li class="breadcrumb-item">
    Create New
</li>
@endsection

@section('content-body')    
    
    <div class="mt-2 p-3 card">

        @include('admin.layouts.message')
        
        <div class="col-8">
            <form action="{{ route('income_expense.store_daily_transactions') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="account_head_id" class="form-label">Account/Expense Head</label>

                    <select class="form-control" id="account_head_id" name="account_head_id" value="{{ old('account_head_id') }}" required autofocus tabindex="1">
                        <option value="">Select Account Head</option>
                        @foreach($account_heads AS $pid => $parent)
                        <optgroup label="{{ $parent }}">
                            @foreach($account_sub_heads AS $child)
                                @if($pid==$child->parent_id)
                                <option value="{{ $child->id }}">
                                    {{ $child->account_head_name }}
                                </option>
                                @endif
                            @endforeach                            
                        </optgroup>
                        @endforeach
                    </select>
                    @error('account_head_id') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required placeholder="Amount" tabindex="2" step="0.01">
                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="debited_to" class="form-label">Debited To</label>
                    <input type="text" class="form-control" id="debited_to" name="debited_to" value="{{ old('debited_to') }}" required placeholder="Debited To" tabindex="3">
                    @error('debited_to') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Pariticular</label>
                    <textarea class="form-control" id="remarks" name="remarks" tabindex="4" cols="5">{{ old('remarks') }}</textarea>
                    @error('remarks') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="" class="btn btn-warning">Reset</a>
                    <a href="{{route('income_expense.daily_transactions')}}" class="btn btn-info">Return back</a>
                </div> 
                
            </form>   
        </div>
    </div>
@endsection

@section('footerjs')

<script></script>
@endsection
