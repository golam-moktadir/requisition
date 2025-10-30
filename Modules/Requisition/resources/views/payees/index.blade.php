@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('payee.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('payee.create') }}" class="btn btn-sm btn-primary">
                Add New
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white text-center">
                    <th>#</th>
                    <th>Payee Name</th>
                    <th>Account Holder Name</th>
                    <th>Account Number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($result AS $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->payee_name }}</td>
                    <td>{{ $row->account_holder_name }}</td>
                    <td>{{ $row->account_number }}</td>
                    <td class="text-center">{{ $row->phone }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->address }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group"> 
                            <a href="{{ route('payee.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a> 
                            <form action="{{ route('payee.destroy', $row->id) }}" method="POST" style="display:hidden;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this Item?')">
                                    Delete
                                </button>
                            </form>           
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
