@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('bank.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('bank.create') }}" class="btn btn-sm btn-primary">
                Add New
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white text-center">
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Bank Name</th>
                    <th>Account Holder Name</th>
                    <th>Account Number</th>
                    <th>Account Type</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($result AS $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->company_name }}</td>
                    <td>{{ $row->bank_name }}</td>
                    <td>{{ $row->account_holder_name }}</td>
                    <td class="text-center">{{ $row->account_no }}</td>
                    <td>{{ ucfirst($row->account_type) }}</td>
                    <td>{{ $row->branch_name }}</td>
                    <td>{{ $row->branch_address }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group"> 
                            <a href="{{ route('bank.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <a href="{{ route('bank.cheque-list', $row->id) }}" class="btn btn-sm btn-info">
                                cheques
                            </a>           
                        </div>    
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>        
    </div>
@endsection

@section('footerjs')


@endsection
