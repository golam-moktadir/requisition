@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('purpose.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('purpose.create') }}" class="btn btn-sm btn-primary">
                Add New
            </a>
        </p>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white">
                    <th style="width: 60px;">#</th>
                    <th>Purpose Name</th>
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
                    <td>{{ $row->purpose_name }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Action Buttons">
                            <a href="{{ route('purpose.edit', $row->id) }}" class="btn btn-primary btn-sm rounded-start">Edit</a>
                            <form action="{{ route('purpose.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Delete this item?');" class="m-0 p-0 d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm rounded-start-0 rounded-end">Delete</button>
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
