@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Create
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <form action="{{ route('requisition.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row my-1">
                <div class="col-sm-6">
                    <label for="company_id" class="form-label">Company Name</label>
                    <select class="form-control" id="company_id" name="company_id" required tabindex="1" autofocus>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="purpose_id" class="form-label">Purpose Name</label>
                    <select class="form-control" id="purpose_id" name="purpose_id" required tabindex="2" autofocus>
                        <option value="">Select Purpose</option>
                        @foreach($purposes as $purpose)
                            <option value="{{ $purpose->id }}" {{ old('purpose_id') == $purpose->id ? 'selected' : '' }}>{{ $purpose->purpose_name }}</option>
                        @endforeach
                    </select>
                    @error('purpose_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
            </div>
            <div class="row my-1">
                <div class="col-sm-6">
                    <label for="payee_id" class="form-label">Payee Name</label>
                    <select class="form-control" id="payee_id" name="payee_id" tabindex="2" autofocus>
                        <option value="">Select Payee</option>
                        @foreach($payees as $row)
                            <option value="{{ $row->id }}" {{ old('payee_id') == $row->id ? 'selected' : '' }}>{{ $row->payee_name }}</option>
                        @endforeach
                    </select>
                    @error('payee_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
            </div>
<!--             <div class="row my-2">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-sm btn-info" id="add-btn">Add Item</button>
                </div>
            </div> -->
            <div class="row my-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" id="item-table">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be appended here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Total : <span id="grand_total_in_word" class="text-danger"></span></td>
                                <td id="grand_total_amount" align="right"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-sm btn-info" id="add-btn">Add Particular</button>
                </div>
            </div>
            <div id="file-inputs">                
                <div class="row my-1">
                    <div class="col-sm-6">
                        <label for="files" class="form-label">Attach Files</label>
                        <input type="file" class="form-control" id="files" name="files[]">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <button type="button" class="btn btn-sm btn-secondary" id="add-more">Add More</button>
            </div>
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="reset" class="btn btn-warning">Reset</button>
                <a href="{{route('requisition.index')}}" class="btn btn-info">Return back</a>
            </div>   
        </form>   
    </div>
    <!-- Add Item Modal -->
    <div class="modal fade" id="item-modal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="item-form">
                        <div class="mb-3">
                            <input type="hidden" id="index" value="">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="6" maxlength="1000"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required placeholder="Amount" step="0.01">
                            <p><strong>In Words:</strong> <span id="inWords" class="text-danger"></span></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="item-btn">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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

        $('#add-btn').on('click', function() {
            $('#item-modal').modal('show');
            $('#item-form')[0].reset();
            $("#inWords").text('');
        });

        $("#item-btn").on('click', function(){
            let description = $("#description").val().trim();
            let amount = parseFloat($("#amount").val());
            let index = $("#index").val();

            if (amount > 0 && description !== "") {
                if (index !== "") {
                    let row = $("#item-table tbody tr").eq(index);
                    row.find('.description')
                        .html(description + "<input type='hidden' name='description[]' value='"+description+"'>");
                    row.find('.amount')
                        .html(amount + "<input type='hidden' name='amount[]' value='"+amount+"'>");
                }
                else{
                    let row = "";
                    row += "<tr>"
                    row += "<td class='text-center'></td>";
                       row += "<td class='editable description'>"+description+
                                    "<input type='hidden' name='description[]' value='"+description+"'></td>";
                    row += "<td class='text-end editable amount'>"+amount+
                                    "<input type='hidden' name='amount[]' value='" + amount + "'></td>";
                    row += "<td class='text-center'>"+
                                "<a href='javascript:void(0)' class='btn btn-sm btn-warning edit-btn'>Edit</a> "+
                                "<a href='javascript:void(0)' class='btn btn-sm btn-danger delete-btn'>Remove</a>"+
                            "</td>";
                    row += "</tr>";
                    $("#item-table tbody").append(row);
                }
                $('#item-modal').modal('hide');
                updateSerialNumbers();
                updateGrandTotal();
            }
            else{
                alert('Some Error ! Please Valid Input');
            }
            $('#index').val(""); // reset edit mode
        });

        $(document).on('click', '.edit-btn', function() {
            var row = $(this).closest('tr'); // get the clicked row
            var index = row.index();          // row index
            let description = row.find("input[name='description[]']").val();
            let amount      = row.find("input[name='amount[]']").val(); 

            $("#index").val(index);
            $('#description').val(description);
            $('#amount').val(amount);

            $('#item-modal').modal('show');
            $("#inWords").text(numberToWords(amount));
        });

        $('#item-table tbody').on('click', '.delete-btn', function (){ 
            if(confirm("Press a button!")){
                $(this).closest('tr').remove(); 
                updateSerialNumbers();
                updateGrandTotal();
            }
        });
    });

    function updateSerialNumbers(){
        $("#item-table tbody tr").each(function (index) {
            $(this).find("td:first").text(index + 1);
        });
    }

    function updateGrandTotal() {
        let grand_total_amount = 0;
        $(".amount").each(function () {
            grand_total_amount += parseFloat($(this).text());
        });

        $("#grand_total_amount").text(grand_total_amount);
        $("#grand_total_in_word").text(numberToWords(grand_total_amount));
    }

    function numberToWords(num) {
        const a = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
            'Eighteen', 'Nineteen'
        ];
        const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function inWords(n) {
            if (n < 20) return a[n];
            if (n < 100) return b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '');
            if (n < 1000) return a[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + inWords(n % 100) : '');
            if (n < 100000) return inWords(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + inWords(n % 1000) : '');
            if (n < 10000000) return inWords(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + inWords(n % 100000) : '');
            return inWords(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + inWords(n % 10000000) : '');
        }

        if (num === 0) return 'Zero Taka';

        const parts = num.toString().split('.');
        const taka = parseInt(parts[0]);
        const paisa = parts[1] ? parseInt(parts[1].substring(0, 2).padEnd(2, '0')) : 0;

        let words = '';
        if (taka > 0) words += inWords(taka) + ' Taka';
        if (paisa > 0) words += (words ? ' and ' : '') + inWords(paisa) + ' Paisa';

        return words || 'Zero Taka';
    }

    // attach listener
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value);
        document.getElementById('inWords').textContent = isNaN(amount) ? '' : numberToWords(amount);
    });
    </script>

@endsection
