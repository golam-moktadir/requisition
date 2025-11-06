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
                Add New
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white text-center">
                    <th>#</th>
                    <th>Account Number</th>
                    <th>Check Number</th>
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($result AS $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $row->bank->account_no }}</td>
                    <td class="text-center">{{ $row->cheque_no }}</td>
                    <td class="text-center">
                        <form action="{{ route('bank.active-toggle', ['id' => $row->id]) }}" method='POST' style='display:inline;'>
                            @csrf
                            @method('PATCH')
                            @if($row->status == 2)
                                <button type="submit" onclick="return confirm('Are you sure ?')" class="btn btn-sm btn-primary">Set Active</button>
                            @elseif ($row->status == 1)
                                <button type="submit" onclick="return confirm('Are you sure ?')" class="btn btn-sm btn-secondary">Set Inactive</button>
                            @endif
                        </form>

                        <form action="{{ route('bank.used-toggle', ['id' => $row->id]) }}" method='POST' style='display:inline;'>
                            @csrf
                            @method('PATCH')
                            @if($row->status == 1)
                                <button type="submit" onclick="return confirm('Are you sure ?')" class="btn btn-sm btn-warning">Mark as Used</button>
                            @elseif ($row->status == 3)
                                <button type="submit" onclick="return confirm('Are you sure ?')" class="btn btn-sm btn-danger">Mark as Unused</button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('footerjs')
@endsection
