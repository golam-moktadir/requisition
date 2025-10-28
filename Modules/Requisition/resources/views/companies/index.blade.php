@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('company.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('company.create') }}" class="btn btn-sm btn-primary">
                Add New
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white">
                    <th style="width: 60px;">#</th>
                    <th>Company Name</th>
                    <th style="width: 130px;">Phone</th>
                    <th style="width: 135px;">Email</th>
                    <th style="width: 130px;">Web Address</th>
                    <th style="width: 100px;">Address</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 140px;">Action</th>                
                </tr>
            </thead>
            <tbody>
                @php 
                    $i = 1;
                @endphp
                @foreach($result AS $row)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $row->company_name }}</td>
                    <td class="text-center">{{ $row->phone }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->website }}</td>
                    <td>{{ $row->address }}</td>
                    <td class="text-center">{{ $row->status_text }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group"> 
                            <a href="{{ route('company.show', $row->id) }}" class="btn btn-sm btn-info"> View
                            </a>
                            <a href="{{ route('company.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a> 
<!--                             <form action="{{ route('company.destroy', $row->id) }}" method="POST" style="display:hidden;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this company?')">
                                    Delete
                                </button>
                            </form>  -->          
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
