@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('bank.index') }}">Bank Information</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('bank.cheque-list', ['id' => $single->id]) }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Add Cheque
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-6">
            <form action="{{ route('bank.save-cheques', ['id' => $single->id]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="bank_id" class="form-label">Company Name</label>
                    <input class="form-control" id="bank_id" value="{{ $single->company->company_name }}" tabindex="1" disabled>
                </div>
                <div class="mb-3">
                    <label for="bank_id" class="form-label">Bank Name</label>
                    <input class="form-control" id="bank_id" value="{{ $single->bank_name }}" tabindex="2" disabled>
                </div>
                <div class="mb-3">
                    <label for="start_no" class="form-label">Start Cheque Number</label>
                    <input type="text" class="form-control" id="start_no" name="start_no"
                           value="{{ old('start_no') }}" placeholder="Enter Start cheque number" required tabindex="3">
                    @error('start_no')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="end_no" class="form-label">End Cheque Number</label>
                    <input type="text" class="form-control" id="end_no" name="end_no"
                           value="{{ old('end_no') }}" placeholder="Enter End cheque number" required tabindex="4">
                    @error('end_no')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="btn-group mt-2" role="group">
                    <button type="submit" class="btn btn-primary">Save Cheque</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{ route('bank.cheque-list', ['id' => $single->id]) }}" class="btn btn-info">Back to List</a>
                </div>
            </form> 
        </div>
    </div>
@endsection
@section('footerjs')
@endsection
