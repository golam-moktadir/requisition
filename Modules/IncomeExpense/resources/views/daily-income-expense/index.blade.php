@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.daily_transactions') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
<div class="mt-1 p-2 card">
    @include('admin.layouts.message')

    <div class="m-2 p-2">

        <p>
            <a href="{{ route('income_expense.create_daily_transactions') }}" class="btn btn-md btn-primary">
                Add Daily Transaction
            </a>
        </p>

        <form class="d-flex align-items-center gap-3" action="{{ route('income_expense.daily_transactions') }}" method="GET"> 
            <input type="date" name="sdate" id="sdate" value="{{ $sdate }}" class="form-control" placeholder="Search Date" aria-label="Search Date" tabindex="1" autofocus required/>
            <select class="form-control" name="account_head" tabindex="2">
                <option value="">Select Account/Expense Head</option>
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
            <button class="btn btn-md btn-success" name="action" value="search" type="submit">Search</button>
            @csrf
        </form>        
    </div>

    <table class="table table-sm table-bordered table-hover">
        <thead>
            <tr class="table-primary text-white">
                <th style="width: 60px;">#</th>
                <th style="width: 150px;">Account Head</th>
                <th style="width: 150px;">Expense Head</th>
                <th style="width: 110px;">Entry Date</th>
                <th style="width: 110px;">Created By</th>
                <th style="width: 80px;">Amount (TK)</th>
                <th>Particulars</th>
                <th style="width: 140px;">Action</th>                
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp
            @foreach($daily_transactions AS $trans)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                @foreach($account_heads AS $pid => $parent)
                    @if($pid==$trans->account_head->parent_id)
                        {{ $parent }}
                    @endif
                @endforeach                    
                </td>
                <td>{{ $trans->account_head->account_head_name }}</td>                
                <td>{{ $trans->created_at }}</td>                
                <td>{{$trans->user->name}}</td>
                <td style="text-align: right;">
                    {{ $trans->amount }} @php $balance += $trans->amount; @endphp
                </td>
                <td>{{ $trans->remarks }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ route('income_expense.show_tran_invoice', $trans->id) }}" target="_blank" class="btn btn-sm btn-primary">Show</a>
                        <form action="{{ route('income_expense.delete_daily_transactions', $trans->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                        </form>                                                
                    </div>                    
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-primary text-white">
                <th style="width: 60px;">#</th>
                <th style="width: 250px;" colspan="4">Total</th>
                <th style="width: 130px; text-align: right;">
                    {{ number_format($balance, 2) }}
                </th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</div>    
@endsection

{{-- @section('footerjs')@endsection --}}