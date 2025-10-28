@extends('admin.layouts.datatableWithForm')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('admin.invoice.index') }}">Invoice</a>
</li>
<li class="breadcrumb-item">
    All Invoice
</li>
@endsection

@section('content-body')
<style>
    .fontsize{font-size: 80%;}     
</style>
<div class="col-lg-12 mb-2 order-0 mb-3">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="card-body">
            <h5 class="card-title text-primary">
                {{ $title_sub }}
            </h5>
            
            @include('admin.layouts.message')            

            <div class="row">
                <div class="col-md-12">             
                    <form class="form-inline" id="invoiceSearchForm" name="invoiceSearchForm" action="{{ route('admin.invoice2.allInvoice') }}" method="GET" autocomplete="off">
                        <div class="row mb-2">      
                            
                            <div class="col-md-3 mb-3">
                                <label for="shop_id" class="form-label">Organization/Shop</label>
                                <select name="shop_id" tabindex="6" class="form-select @error('status') is-invalid @enderror" id="shop_id" aria-label="org_name" >           
                                    <option value="">Select an Organization</option>
                                    <option value="{{ $orgs[0]->id }}"{{$orgs[0]->id == old('shop_id', $shop_id)  ? ' selected' : ''}}>{{ $orgs[0]->org_name }}</option>           
                                    <option value="{{ $orgs[1]->id }}"{{$orgs[1]->id == old('shop_id', $shop_id)  ? ' selected' : ''}}>{{ $orgs[1]->org_name }}</option>
                                </select>
                                @error('shop_id')
                                <div class="form-text text-danger">{{ $message }}</div>
                                @enderror                        
                            </div>  
                                                        
                            <div class="col-md-3 mb-3">
                                <label for="cust_id" class="form-label">Customer</label>
                                <select name="cust_id" tabindex="6" class="form-select @error('status') is-invalid @enderror" id="cust_id" aria-label="org_name" >           
                                    <option value="">Select an Organization</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->cust_id }}"{{$customer->cust_id == old('cust_id', $cust_id)  ? ' selected' : ''}}>
                                        {{ $customer->org_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('cust_id')
                                <div class="form-text text-danger">{{ $message }}</div>
                                @enderror                        
                            </div>  

                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start date (Created date)</label>
                                <input value="{{ old('start_date', $start_date) }}" name="start_date" tabindex="1" type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" placeholder="Start date"  autocomplete="off" const packageName = require('packageName');/>
                                @error('start_date')
                                <div class="form-text text-danger">{{ $message }}</div>
                                @enderror                        
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End date (Created date)</label>
                                <input value="{{ old('end_date', $end_date) }}" name="end_date" tabindex="2" type="date" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="end_date" placeholder="End date"  autocomplete="off" />
                                @error('end_date')
                                <div class="form-text text-danger">{{ $message }}</div>
                                @enderror                        
                            </div> 

                            <div class="col-md-3 mb-3">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <input value="{{ old('invoice_number', $invoice_number) }}" name="invoice_number" tabindex="2" type="text" class="form-control @error('last_edu_certificate') is-invalid @enderror" id="invoice_number" placeholder="Invoice Number [eg: 20240221]"  autocomplete="off" />
                                @error('invoice_number')
                                <div class="form-text text-danger">{{ $message }}</div>
                                @enderror                        
                            </div> 
                        </div>
                        <div class="clearboth"></div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-primary" type="submit" tabindex="20" name="employee-submit">Search</button>
                        </div>                        
                    </form>
                </div>
            </div>  

            <div class="row">
                <div class="col-md-12"> 
                    <div class="table-container">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>S.L.</th>
                                    <th>Shop</th>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Discount Amount</th>
                                    <th>Received Amount</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $key => $invoice )
                                @php
                                    $request_data = json_decode($invoice->request_data);
                                @endphp                             
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td class="text-start">{{ $invoice->shop->org_name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.invoice2.showPdf', $invoice->invoice_id) }}" target="_blank">
                                            INV-{{ $invoice->invoice_number }}
                                        </a>
                                    </td>
                                    <td class="text-start">{{ @$invoice->customer->org_name }}</td>
                                    <td class="text-end">
                                        {{ $invoice->total_amount }}
                                    </td>
                                    <td class="text-end">{{ $invoice->discount_amount }}</td>
                                    <td class="text-end"></td>
                                    <td class="text-center">{{ $invoice->created_at }}</td>
                                    <td class="text-center">
                                        <div class="button-container">
                                            <button class="my-button-11 confirmButton" data-route="{{ route('admin.invoice2.deleteInvoice', $invoice->invoice_id) }}">
                                                Delete 
                                            </button>
                                        </div>
                                    </td>
                                </tr>                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>            
                </div>
            </div>                                                  
        </div>
      </div>
    </div>
  </div>

@endsection

@section('footerjs')
<script>
$(document).ready(function() {
    jQuery('.confirmButton').click(function(event){            
        if (confirm("Are you sure want to delete?")) {
            window.location.href = jQuery(this).data('route');
        }        
    });
});
</script>
@endsection