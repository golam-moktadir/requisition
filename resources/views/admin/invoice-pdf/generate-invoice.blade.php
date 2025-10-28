@extends('admin.layouts.datatableWithForm')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('admin.ledger.index') }}">Ledger</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
<style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        #myInvoiceRowTable{            
            width: 98%;
            margin: 1%;
        }
</style>

<div class="col-lg-12 mb-2 order-0 mb-3">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="card-body">
            <h5 class="card-title text-primary">
                {{ $title_sub }}
            </h5>
            @if(session('success'))     
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>                                       
                </div>
            </div>            
            @endif
            
            {{-- @if($isEdit)
              @include('admin.ledger.edit')
            @else
              @include('admin.ledger.create')              
            @endif --}}

            <form action="{{ route('admin.invoice2.saveInvoice') }}" method="POST">    
                @csrf             
                <h4>Customer Info</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="shop_id" class="form-label">Organization/Shop</label>
                        <select name="shop_id" tabindex="6" class="form-select @error('status') is-invalid @enderror" id="shop_id" aria-label="org_name" >           
                            <option value="">Select an Organization</option>
                            <option value="{{ $orgs[0]->id }}"{{$orgs[0]->id == old('shop_id')  ? ' selected' : ''}}>{{ $orgs[0]->org_name }}</option>           
                            <option value="{{ $orgs[1]->id }}"{{$orgs[1]->id == old('shop_id')  ? ' selected' : ''}}>{{ $orgs[1]->org_name }}</option>
                        </select>
                        @error('shop_id')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>                     
                    <div class="col-md-4 mb-3">
                        <label for="cust_id" class="form-label">Customer</label>
                        <select name="cust_id" tabindex="6" class="form-select @error('status') is-invalid @enderror" id="cust_id" aria-label="cust_id" required>           
                            <option value="" data-object="{}">Select an Customer</option>
                            <option value="0" data-object="{}">Other Customer</option>
                            @foreach ($customers as $key => $customer )
                            <option value="{{ $customer->cust_id }}" data-object="{{ $customer }}">
                                {{ $customer->first_name }} - {{ $customer->org_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('cust_id')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>                    
                </div> 
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="first_name" class="form-label">Customer Name</label>
                        <input value="{{ old('first_name') }}" name="cust_first_name" tabindex="1" type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" placeholder="Customer Name" autofocus autocomplete="first_name" required maxlength="80"/>
                        @error('first_name')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cust_mobile_no" class="form-label">Mobile No</label>
                        <input value="{{ old('cust_mobile_no') }}" name="cust_mobile_no" tabindex="4" type="text" class="form-control @error('cust_mobile_no') is-invalid @enderror" id="cust_mobile_no" placeholder="Mobile No"  autocomplete="cust_mobile_no" maxlength="20" required/>
                        @error('cust_mobile_no')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="org_address" class="form-label">Address</label>
                        <input value="{{ old('org_address') }}" name="cust_org_address" tabindex="3" type="text" class="form-control @error('org_address') is-invalid @enderror" id="org_address" placeholder="Address"  autocomplete="org_address" required maxlength="250" />
                        @error('org_address')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>

                    <div class="col-md-4 mb-3" style="display: none;">
                        <label for="org_name" class="form-label">Organization name</label>
                        <input value="{{ old('org_name') }}" name="cust_org_name" tabindex="2" type="text" class="form-control @error('org_name') is-invalid @enderror" id="org_name2" placeholder="Organization name"  autocomplete="org_name" maxlength="255" />
                        @error('org_name')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>
                    <div class="col-md-4 mb-3" style="display: none;">
                        <label for="cust_email_address" class="form-label">Email Address</label>
                        <input value="{{ old('cust_email_address') }}" name="cust_email_address" tabindex="5" type="text" class="form-control @error('cust_email_address') is-invalid @enderror" id="cust_email_address" placeholder="Email Address" autocomplete="cust_email_address" maxlength="100"/>
                        @error('cust_email_address')
                        <div class="form-text text-danger">{{ $message }}</div>
                        @enderror                        
                    </div>
                </div> 
                <div class="row mb-3">                    
                    <table id="myInvoiceRowTable">
                        <thead>
                            <tr>
                                <th style="">Description</th>
                                <th style="width: 110px;">Unit/Sqft/KG</th>
                                <th style="width: 110px;">Quantity</th>
                                <th style="width: 110px;">Unit Price</th>
                                <th style="width: 110px;">Total Amount</th>
                                <th style="width: 110px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="myInvoiceRowTableTbody">
                            <tr>
                                <td><input type="text" required name='description[]' placeholder="Description" style="width: 99%;"></td>
                                <td><input type="text" required name='unit[]' placeholder="Unit/Sqft/KG" style="width: 99%;"></td>
                                <td><input type="text" required name='quantity[]' placeholder="Quantity" style="width: 99%;"></td>
                                <td><input type="text" required name='price[]' placeholder="Price" style="width: 99%;"></td>
                                <td><input type="text" required name='total_amount[]' placeholder="Amount" style="width: 99%;" class='total_amount' value="0"></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="2" style="text-align: right;">Subtotal</th>
                                <th>
                                    <input type="text" style="width: 99%" name="subtotal" readonly required id="subtotal" value="0">
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="2" style="text-align: right;">Discount</th>
                                <th>
                                    <input type="text" style="width: 99%" name="discount" required id="discount" value="0" onkeyup="calculateInvoiceAmount()">
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="2" style="text-align: right;">Previous Due</th>
                                <th>
                                    <input id="prev_due_amount" type="text" style="width: 99%" name="prev_due_amount" required value="0" onkeyup="calculateInvoiceAmount()">
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="2" style="text-align: right;">Paid</th>
                                <th>
                                    <input id="paid_amount" type="text" style="width: 99%" name="paid_amount" required value="0" onkeyup="calculateInvoiceAmount()">
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <th colspan="2" style="text-align: right;">Total Amount</th>
                                <th>
                                    <input id="final_amount" type="text" style="width: 99%" name="final_amount" readonly required value="0">
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <button onclick="addRow()" type="button">Add Row</button>                
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="cust_id" class="form-label">Note/Remarks</label>
                        <input type="text" class="form-control" value="" placeholder="Note" name="remarks">
                    </div>                    
                </div>

                <br>
                <button type="submit" onclick="return confirm('Are you sure?')">
                    Save Invoice
                </button>   
            </form>                                                               
        </div>
      </div>
    </div>
  </div>
  

@endsection

@section('footerjs')

<script>
    function addRow() {
        const table = document.getElementById("myInvoiceRowTable").getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();

        // Create and insert cells
        const cell1 = newRow.insertCell(0);
        const cell2 = newRow.insertCell(1);
        const cell3 = newRow.insertCell(2);
        const cell4 = newRow.insertCell(3);
        const cell5 = newRow.insertCell(4);
        const cell6 = newRow.insertCell(5);

        // Insert data into the cells
        cell1.innerHTML = `<input type="text" required name='description[]' placeholder="Description" style="width: 99%;">`;
        cell2.innerHTML = `<input type="text" required name='unit[]' placeholder="Unit/Sqft/KG" style="width: 99%;">`;
        cell3.innerHTML = `<input type="text" required name='quantity[]' placeholder="Quantity" style="width: 99%;">`;
        cell4.innerHTML = `<input type="text" required name='price[]' placeholder="Price" style="width: 99%;">`;
        cell5.innerHTML = `<input type="text" required name='total_amount[]' placeholder="Amount" style="width: 99%;" class='total_amount' value='0'>`;
        cell6.innerHTML = `<button type="button" onclick="deleteRow(this)" style="width: 99%;">Delete</button>`;
    }
    
    function calculateInvoiceAmount(){
        var total_amount = 0;
        jQuery('input.total_amount').each(function(index) {
           var amount = parseInt(jQuery(this).val());
           if(amount>0){
            total_amount += amount;
           }
        });
        jQuery('#subtotal').val(total_amount);
        var paid_amount = parseInt(jQuery('#paid_amount').val());
        var prev_due_amount = parseInt(jQuery('#prev_due_amount').val());
        var discount = parseInt(jQuery('#discount').val());
        var final_amount = ((total_amount - paid_amount - discount) + prev_due_amount);
        jQuery('#final_amount').val(final_amount);        
    }

    function deleteRow(button) {
        const row = button.parentNode.parentNode; // Get the row of the button
        row.parentNode.removeChild(row); // Remove the row from the table
        calculateInvoiceAmount();
    }
    
    jQuery('#myInvoiceRowTable').delegate("input.total_amount", "keyup", function(event) {
        calculateInvoiceAmount();
    });
</script>

<script>

// document.addEventListener('contextmenu', event => event.preventDefault());

$(document).ready(function () {
    jQuery('.selectAllCheckbox').click(function () {  
        $('.invoice_items').each(function (index, element) {
            jQuery(this).prop("checked", true);            
        });
    });
    jQuery('.calculateInvoiceAmount').click(function(){
        var final_amount = 0;        
        $('.invoice_items').each(function (index, element) {
            var amount = 0;
            if(jQuery(this).is(':checked')){
                var id = jQuery(this).val();            
                amount = parseInt(jQuery('.invoice_amount' + id ).text());
                final_amount = (final_amount + amount);
            }            
        });        
        $('.input_amount').each(function (index, element) {     
            var amount = parseInt(jQuery(this).val());
            final_amount = (final_amount + amount);            
        }); 
        var discount = parseInt(jQuery('.discount').val());
        final_amount = (final_amount - discount);            

        jQuery('.final_amount').text(final_amount);      
    });
    jQuery('#cust_id').change(function(){
        var value = $( "#cust_id option:selected" ).data('object');
        var object = $( "#cust_id option:selected" ).data('object');
        var first_name = $('#first_name'), org_name = $('#org_name2'), 
        org_address = $('#org_address'), cust_mobile_no = $('#cust_mobile_no'), 
        cust_email_address = $('#cust_email_address');

        first_name.val('');
        org_name.val('');
        org_address.val('');
        cust_mobile_no.val('');
        cust_email_address.val('');

        if(value=='0' || value==''){
            return false;
        }

        first_name.val(object.first_name + ' [' + object.org_name + ']');
        org_name.val(object.org_name);
        org_address.val(object.org_address);
        cust_mobile_no.val(object.cust_mobile_no);
        cust_email_address.val(object.cust_email_address);

        console.info(object);
    });
});    
</script>
@endsection
