@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('bank.index') }}">Bank Information</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('bank.cheque-list', ['id' => $single->id]) }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Cheque List
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <p>
            <a href="{{ route('bank.create-cheque', ['id' => $single->id]) }}" class="btn btn-sm btn-primary">
                Add New Cheque
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-info text-center">
                    <th colspan="5">
                        Bank: {{ $single->bank_name }} &nbsp; | &nbsp; Account No: {{ $single->account_no }}
                    </th>
                </tr>
                <tr class="table-primary text-white text-center">
                    <th>#</th>
                    <!-- <th>Account Number</th> -->
                    <th>Check Number</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($result AS $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <!-- <td class="text-center">{{ $row->bank->account_no }}</td> -->
                    <td class="text-center">{{ $row->cheque_no }}</td>
                    <td class="text-center">{{ $row->status_text }}</td>
                    <td class="">{{ $row->remarks }}</td>
                    <td class="text-center">
                        <a href="{{ route('bank.edit-cheque', ['id' => $row->id]) }}" class="btn btn-sm btn-primary">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('footerjs')
@endsection
