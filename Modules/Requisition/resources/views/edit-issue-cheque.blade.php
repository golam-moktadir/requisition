@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Edit Issue Cheque
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-6">
            <form action="{{ route('requisition.update-issue-cheque', ['id' => $single->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="requisition_no" class="form-label">Requisition No.</label>
                    <input class="form-control" id="requisition_no" value="{{ $single->id }}" tabindex="1" disabled>
                </div>
                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input class="form-control" id="company_name" value="{{ $single->company_name }}" tabindex="2" disabled>
                </div>
                <div class="mb-3">
                    <label for="bank_id" class="form-label">Select Bank</label>
                    <select class="form-select" id="bank_id" name="bank_id" tabindex="3">
                        <option value="">-- Select Bank --</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}" {{ $cheque->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->bank_name }} ({{ $bank->account_no }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cheque_no" class="form-label">Select Cheque Number</label>
                    <select class="form-select" id="cheque_id" name="cheque_id" tabindex="4">
                        <option value="">-- Select Cheque Number --</option>
                        @foreach($cheques as $row)
                            @if($row->bank_id == $cheque->bank_id)
                                <option value="{{ $row->id }}" {{ $row->id == $cheque->id ? 'selected' : '' }}>
                                    {{ $row->cheque_no }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="btn-group mt-2" role="group">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="#" class="btn btn-info">Back to List</a>
                </div>
            </form> 
        </div>
    </div>
@endsection
@section('footerjs')
<script type="text/javascript">
    $('#bank_id').on('change', function() {
        const bank_id = $(this).val();
        $.ajax({
            url : "{{ route('requisition.get-valid-cheque-list') }}",
            type: 'post',
            data: {bank_id: bank_id},
            success: function(response){
                        if(response){
                            $("#cheque_id").html(response.options);
                        }
            }
        });
    });
</script>
@endsection
