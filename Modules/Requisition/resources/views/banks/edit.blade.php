@extends('admin.layouts.master')

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <form action="{{ route($route.'update', ['id' => $single->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row my-1">
                <div class="col-sm-6">
                    <label for="bank_name" class="form-label">Bank Name</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $single->bank_name) }}" placeholder="Bank Name" tabindex="1" autofocus>
                    @error('bank_name') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
            </div>
            <div class="row my-1">
                <div class="col-sm-6">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{route($route.'index')}}" class="btn btn-info">Back</a>
                    </div>   
                </div>
            </div>
        </form>   
    </div>
@endsection
@section('footerjs')
@endsection
