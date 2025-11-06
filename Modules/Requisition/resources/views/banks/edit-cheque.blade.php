@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('bank.index') }}">Bank Information</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('bank.cheque-list', ['id' => $single->bank_id]) }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Add Cheque
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-6">
            <form action="{{ route('bank.activity-cheque-status', ['id' => $single->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="bank_id" class="form-label">Company Name</label>
                    <input class="form-control" id="bank_id" value="{{ $single->bank->company->company_name }}" tabindex="1" disabled>
                </div>
                <div class="mb-3">
                    <label for="bank_id" class="form-label">Bank Name</label>
                    <input class="form-control" id="bank_id" value="{{ $single->bank->bank_name }}" tabindex="2" disabled>
                </div>
                <div class="mb-3">
                    <label for="bank_id" class="form-label">Account No</label>
                    <input class="form-control" id="bank_id" value="{{ $single->bank->account_no }}" tabindex="2" disabled>
                </div>
                <div class="mb-3">
                    <label for="cheque_no" class="form-label">Cheque Number</label>
                    <input type="text" class="form-control" id="cheque_no" value="{{ $single->cheque_no }}" tabindex="3" disabled>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" tabindex="4">
                        <option value="1" {{ $single->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ $single->status == 2 ? 'selected' : '' }}>Inactive</option>
                        <option value="3" {{ $single->status == 3 ? 'selected' : '' }}>Used</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{ $single->remarks }}" placeholder="Enter Remarks" tabindex="5">
                </div>
                <div id="file-inputs">                
                    <div class="mb-1">
                        <label for="files" class="form-label">Attach Files</label>
                        <input type="file" class="form-control" id="files" name="files[]">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-secondary" id="add-more">Add More</button>
                </div>
                <div class="btn-group mt-2" role="group">
                    <button type="submit" class="btn btn-primary">Save Cheque</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{ route('bank.cheque-list', ['id' => $single->bank_id]) }}" class="btn btn-info">Back to List</a>
                </div>
            </form> 
        </div>
    </div>
@endsection
@section('footerjs')
    <script type="text/javascript">
    $(document).ready(function() {
        $('#add-more').on('click', function() {
            let input = $('<div class="mb-1"><input type="file" name="files[]" class="form-control"></div>');
            $('#file-inputs').append(input);
        });
    });
    </script>
@endsection
