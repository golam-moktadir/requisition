@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('purpose.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Create
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   

        <div class="col-8">
            <form action="{{ route('purpose.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="purpose_name" class="form-label">Purpose Name</label>
                    <input type="text" class="form-control" id="purpose_name" name="purpose_name" 
                           value="{{ old('purpose_name') }}" placeholder="Purpose Name" tabindex="1" autofocus>
                    @error('purpose_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{route('purpose.index')}}" class="btn btn-info">Return back</a>
                </div>   
            </form>   
        </div>
    </div>
@endsection
@section('footerjs')
@endsection
