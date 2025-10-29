@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Show
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
            <div class="row">
                <div class="mb-3 col-4">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="company_name" value="{{ $single->company_name }}" disabled>
                </div>
                <div class="mb-3 col-4">
                    <label for="purpose_name" class="form-label">Purpose Name</label>
                    <input type="text" class="form-control" id="purpose_name" value="{{ $single->purpose_name }}" disabled>
                </div>
                <div class="mb-3  col-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" tabindex="2" disabled>{{ $single->description }}</textarea>
                </div>
                <div class="mb-3 col-4">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" value="{{ $single->amount }}" required tabindex="3" step="0.01" disabled>
                </div>
                <div class="mb-3 col-4">
                    <label for="requested_to" class="form-label">Requested To</label>
                    <input type="text" class="form-control" id="requested_to" value="{{ $single->requested_to }}" tabindex="4" disabled>
                </div>
                <div class="mb-3 col-4">
                    <label class="form-label">Attach Files</label>
                    <ul class="list-group">
                        @foreach($files as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ asset('storage/requisitions/'.$file->file_name) }}" target="_blank">
                                    {{ $file->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mb-3 col-4">
                    <label for="bank_id" class="form-label">Bank Name</label>
                    <input type="text" class="form-control" id="bank_id" name="bank_id" value="" tabindex="5">
                </div>
                <div class="mb-3 col-4">
                    <label for="branch_name" class="form-label">Branch Name</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" tabindex="6">
                </div>
                <div class="mb-3 col-4">
                    <label for="branch_name" class="form-label">A/C Name</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" tabindex="6">
                </div>
                <div class="mb-3 col-4">
                    <label for="branch_name" class="form-label">A/C No</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" tabindex="6">
                </div>
                <div class="mb-3 col-4">
                    <label for="branch_name" class="form-label">Cheque No</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" tabindex="6">
                </div>
                <div class=" mb-3 col-4">
                    <label for="activity_date" class="form-label">Date</label>
                    <input type="text" class="form-control" id="activity_date" name="activity_date" value="" tabindex="2" autocomplete="off">
                </div>
                <div class="mb-3 col-4">
                    <label for="branch_name" class="form-label">Cheque Amount</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" tabindex="6">
                </div>
            </div>  
    </div>

@endsection

@section('footerjs')
<script type="text/javascript">
    $(document).ready(function(){
        flatpickr("#activity_date", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            // maxDate: "today"    
        });
    });
</script>

@endsection
