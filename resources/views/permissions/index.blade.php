@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('permission.create') }}" class="btn btn-sm btn-primary">
                Add New
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white text-center">
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($result AS $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->email }}</td>                   
                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Basic example"> 
                            <a href="{{ route('requisition.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                Edit
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
<script type="text/javascript">
    $(document).ready(function(){
        flatpickr("#from_date, #to_date", {
            dateFormat: "Y-m-d",
            //defaultDate: "today",
            // maxDate: "today"    
        });
    });
</script>

@endsection
