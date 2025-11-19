@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Edit Issue Payment
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
            <form action="{{ route('requisition.update-payment', ['id' => $payment->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row my-1">
                    <div class="col-6">
                        <label for="requisition_no" class="form-label">Requisition No.</label>
                        <input class="form-control" id="requisition_no" value="{{ $payment->req_no }}" tabindex="1" disabled>
                        <input type="hidden" id="requisition_id" name="requisition_id" value="{{ $payment->requisition_id }}">
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-6">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input class="form-control" id="company_name" value="{{ $payment->requisition->company->company_name }}" tabindex="2" disabled>
                    </div>
                </div>
                <div class="row my-1">
                    <div class="col-6">
                        <label for="payment_type" class="form-label">Payment Type</label>
                        <select class="form-select" id="payment_type" name="payment_type" tabindex="3">
                            <option value="">-- Select Payment Type --</option>
                            <option value="1" {{ $payment->payment_type == 1 ? 'selected' : '' }}>Cheque</option>
                            <option value="2" {{ $payment->payment_type == 2 ? 'selected' : '' }}>Cash</option>
                            <option value="3" {{ $payment->payment_type == 3 ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>
                </div>
                <!-- Cheque Section -->
                <div id="cheque-section" style="display:none;">
                    <div class="row my-1">
                        <div class="col-6">
                            <label for="bank_id" class="form-label">Select Bank</label>
                            <select class="form-select" id="bank_id" name="bank_id" tabindex="3">
                                <option value="">-- Select Bank --</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}" 
                                        {{ optional($payment->cheque)->bank_id == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} ({{ $bank->account_no }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row my-1">
                        <div class="col-6">
                            <label for="cheque_id" class="form-label">Select Cheque Number</label>
                            <select class="form-select" id="cheque_id" name="cheque_id" tabindex="4">
                            @foreach($cheques as $row)
                                @if(optional($payment->cheque)->bank_id == $row->bank_id)
                                    <option value="{{ $row->id }}" {{ $row->id == $payment->cheque_id ? 'selected' : '' }}>
                                        {{ $row->cheque_no }}
                                    </option>
                                @endif
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Cash Section -->
                <div id="cash-section" style="display:none;">
                    <div class="row my-1">
                        <div class="col-6">
                            <label for="cash_description" class="form-label">Description</label>
                            <textarea class="form-control" id="cash_description" name="cash_description" rows="2" placeholder="Enter description">{{ $payment->cash_description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- File Upload (always visible) -->
                <div id="file-inputs">                
                    <div class="row my-1">
                        <div class="col-sm-6">
                            <label for="files" class="form-label">Attach Files</label>
                            <input type="file" class="form-control" id="files" name="files[]">
                        </div>
                        <div class="col-sm-6">
                            <label for="title" class="form-label">Titles</label>
                            <input type="text" class="form-control" id="title" name="title[]" placeholder="Set Title">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-secondary" id="add-more">Add More</button>
                </div>
                @if($payment->files)
                <div class="row my-1">
                    <div class="col-sm-6">
                        <label class="form-label">Existing Files</label>
                        <ul class="list-group">
                            @foreach(json_decode($payment->files) as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ asset('storage/payments/'.$file->name) }}" target="_blank">
                                        {{ $file->title ?? $file->name }}
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger remove-file" data-file-id="{{ $file->name }}">
                                        Delete
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <div class="btn-group mt-2" role="group">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{ route('requisition.show', ['id' => $payment->requisition_id]) }}" class="btn btn-info">Back</a>
                </div>
            </form> 
        </div>
    </div>
@endsection
@section('footerjs')
<script type="text/javascript">
    $('#payment_type').on('change', function() {
        const paymentType = $(this).val();
        togglePaymentSections($(this).val());
    });

    const paymentType = $('#payment_type').val();
    togglePaymentSections(paymentType);

    function togglePaymentSections(paymentType) {
        if (paymentType == '1') {
            $('#cheque-section').show();
            $('#cash-section').hide();
        } 
        else if (paymentType == '2' || paymentType == '3' ) { // Cash or Bank Transfer
            $('#cash-section').show();
            $('#cheque-section').hide();
        } 
        else {
            $('#cheque-section, #cash-section').hide();
        }
    }

    $('#add-more').on('click', function() {
        let input = $('<div class="row mb-1">'+
                            '<div class="col-sm-6">'+
                                '<input type="file" name="files[]" class="form-control">'+
                            '</div>'+
                            '<div class="col-sm-6">'+
                                '<input type="text" class="form-control" id="title" name="title[]" placeholder="Set Title">'+
                            '</div>'+
                        '</div>'
                    );
        $('#file-inputs').append(input);
    });
    
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

    const deleteFileRoute = "{{ route('requisition.payment.file.delete', ['id' => ':id', 'file' => ':file']) }}";

    $(".remove-file").on('click', function() {
        const file = $(this).data('file-id');
        const id   = "{{ $payment->id }}";
        const button  = $(this);
        const url     = deleteFileRoute.replace(':id', id).replace(':file', file);

        if (confirm('Are you sure you want to delete this file?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                success:function(response) {
                    if(response){
                        button.closest('li').remove();
                    }
                }
            });
        }
    });  
</script>
@endsection
