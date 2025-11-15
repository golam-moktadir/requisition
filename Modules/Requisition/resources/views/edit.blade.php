@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Edit
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <form action="{{ route('requisition.update', $single->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="row my-1">
                <div class="col-sm-6">
                    <label for="company_id" class="form-label">Company Name</label>
                    <select class="form-control" id="company_id" name="company_id" required tabindex="1" autofocus>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $single->company_id) == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
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
                            <option value="{{ $purpose->id }}" {{ old('purpose_id', $single->purpose_id) == $purpose->id ? 'selected' : '' }}>{{ $purpose->purpose_name }}</option>
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
                            <option value="{{ $row->id }}" {{ old('payee_id', $single->payee_id) == $row->id ? 'selected' : '' }}>{{ $row->payee_name }}</option>
                        @endforeach
                    </select>
                    @error('payee_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
            </div>
            <div class="row my-2">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-sm btn-info" id="add-btn">Add Item</button>
                </div>
            </div>
            <div class="row my-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" id="item-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $row)
                            <tr>
                                <td class='text-center'>{{ $loop->iteration }}</td>
                                <td class='editable description'>{{ $row->description }}
                                    <input type='hidden' name='description[]' value='{{ $row->description }}'>
                                </td>
                                <td class='text-end editable amount'>{{ $row->amount }}
                                    <input type='hidden' name='amount[]' value='{{ $row->amount }}'>
                                </td>
                                <td class='text-center'><a href='javascript:void(0)' class='btn btn-sm btn-danger delete-btn'>Remove</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            @if($files->count() > 0)
            <div class="mb-3">
                <label class="form-label">Existing Files</label>
                <ul class="list-group">
                    @foreach($files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ asset('storage/requisitions/'.$file->file_name) }}" target="_blank">
                                {{ $file->file_name }}
                            </a>
                            <button type="button" class="btn btn-sm btn-danger remove-file" data-file-id="{{ $file->file_name }}">
                                Delete
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-primary">Save</button>
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
            let description = $("#description").val();
            let amount = $("#amount").val();
            if(amount){
                let row = "";
                row += "<tr>"
                row += "<td class='text-center'></td>";
                // row += "<td class='editable description'>"+description+"</td>";
                   row += "<td class='editable description'>"+description+
                                "<input type='hidden' name='description[]' value='"+description+"'></td>";
                row += "<td class='text-end editable amount'>"+amount+
                                "<input type='hidden' name='amount[]' value='" + amount + "'></td>";
                row += "<td class='text-center'><a href='javascript:void(0)' class='btn btn-sm btn-danger delete-btn'>Remove</a></td>";
                row += "</tr>";
                $("#item-table tbody").append(row);
                $('#item-modal').modal('hide');
                updateSerialNumbers();
                updateGrandTotal();
                item_qty = 0;
                item_price = 0;
                total_price = 0;
            }
       });

        const deleteFileRoute = "{{ route('requisition.file.destroy', ':id') }}";

        $(".remove-file").on('click', function() {
            const file_id = $(this).data('file-id');
            const button = $(this);
            const url = deleteFileRoute.replace(':id', file_id);

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
    });

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
