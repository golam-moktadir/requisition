@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Issue Cheque
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-6">
            <form action="{{ route('requisition.save-payment') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="requisition_no" class="form-label">Requisition No.</label>
                    <input class="form-control" id="requisition_no" value="{{ $single->id }}" tabindex="1" disabled>
                    <input type="hidden" id="requisition_id" name="requisition_id" value="{{ $single->id }}">
                </div>
                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input class="form-control" id="company_name" value="{{ $single->company_name }}" tabindex="2" disabled>
                </div>
                <div class="mb-3">
                    <label for="payment_type" class="form-label">Payment Type</label>
                    <select class="form-select" id="payment_type" name="payment_type" tabindex="3">
                        <option value="">-- Select Payment Type --</option>
                        <option value="1">Cheque</option>
                        <option value="2">Cash</option>
                    </select>
                </div>

                <!-- Cheque Section -->
                <div id="cheque-section" style="display:none;">
                    <div class="mb-3">
                        <label for="bank_id" class="form-label">Select Bank</label>
                        <select class="form-select" id="bank_id" name="bank_id" tabindex="3">
                            <option value="">-- Select Bank --</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }} ({{ $bank->account_no }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cheque_id" class="form-label">Select Cheque Number</label>
                        <select class="form-select" id="cheque_id" name="cheque_id" tabindex="4">
                            <option value="">-- Select Cheque Number --</option>
                        </select>
                    </div>
                </div>

                <!-- Cash Section -->
                <div id="cash-section" style="display:none;">
                    <div class="mb-3">
                        <label for="cash_amount" class="form-label">Cash Amount</label>
                        <input type="number" class="form-control" id="cash_amount" name="cash_amount" placeholder="Enter amount">
                    </div>
                    <div class="mb-3">
                        <label for="cash_description" class="form-label">Description</label>
                        <textarea class="form-control" id="cash_description" name="cash_description" rows="2" placeholder="Enter description"></textarea>
                    </div>
                </div>

                <!-- File Upload (always visible) -->
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
    $('#payment_type').on('change', function() {
        const paymentType = $(this).val();

        if (paymentType == '1') { // Cheque
            $('#cheque-section').show();
            $('#cash-section').hide();
        } 
        else if (paymentType == '2') { // Cash
            $('#cash-section').show();
            $('#cheque-section').hide();
        } 
        else {
            $('#cheque-section, #cash-section').hide();
        }
    });

    $('#add-more').on('click', function() {
        let input = $('<div class="mb-1"><input type="file" name="files[]" class="form-control"></div>');
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
</script>
@endsection
