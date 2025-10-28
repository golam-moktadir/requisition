<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Invoice - {{ $invoice->invoice_number }}</title>
        <style>
            body {font-family: 'solaimanlipi', sans-serif;}
            @page {
                header: page-header;
                footer: page-footer;
            }   
            .font_color{
                color: red;
            }   
            .default_font_size{
                font-size: 15;
            }   
            .fs9{
                font-size: 12;
            }   
            .fs12{
                font-size: 15;
            }    
            .shop_title{
                font-weight: bold;
                font-size: 32px;
                color: #63308D;
            }    
            .table_border{
                border: 1px solid #ccc;  
                border-collapse: collapse;              
            }   
        </style>
    </head>
    <body>                   
        <htmlpageheader name="page-header">
            <table style="width: 100%;" border="0"  class="">
                <tr>
                    <td style="width: {{ $organizationSetting->id=='1'?'22%':'17%' }}; text-align: right;">
                        <img src="data:image/jpeg;base64,{{ $imageData }}" alt="" style="width: 11%">
                    </td>
                    <td style="text-align: left; padding-left: 2%">
                        <p class="shop_title">{{ $organizationSetting->org_name }}</p>
                        <p class="default_font_size">
                            Address# {{ $organizationSetting->org_address }}. 
                            Mobile# {{ $organizationSetting->org_mobile }}. Email# {{ $organizationSetting->org_email }}
                        </p>
                    </td>
                </tr>
            </table>
        </htmlpageheader>   

        <htmlpagefooter name="page-footer">
            <table border="0" style="width: 100%;" class="default_font_size">
                <tr>
                    <td style="width: 50%; padding-left: 8%">------------------------------</td>
                    <td style="padding-right: 8%; text-align: right;">----------------------------------</td>
                </tr>
                <tr>
                    <td style="width: 50%; padding-left: 10%">Received By</td>
                    <td style="padding-right: 10%; text-align: right;">Manager Signature</td>
                </tr>
                <tr>
                    <td class="fs12" colspan="2">
                        Page {PAGENO} of {nb}. 
                        Invoice No: {{ $invoice->invoice_number }}. 
                        Printed Time: {{ $printed_time }}.
                        Printed By: {{ $printed_by }}.
                    </td>
                </tr>
            </table>
        </htmlpagefooter>  

        <div style="margin-top: 25%">
            <table border="0" style="width: 100%;">
                <tr>
                    <td style="line-height: 7px; width: 70%; background-color: #EE9321;">
                        <div style="max-height: 5px;">
                            &nbsp;
                        </div>
                    </td>
                    <td style="line-height: 7px; width: 10%; text-align: center; font-weight: bold;">                        
                        <div style="max-height: 5px;">
                            INVOICE
                        </div>
                    </td>
                    <td style="line-height: 7px; background-color: #EE9321;">
                        <div style="max-height: 5px;">
                            &nbsp;
                        </div>
                    </td>
                </tr>
            </table>
            <html-separator/>

            <table border="0" style="width:100%; margin-top: 3%; margin-bottom: 1.5%">
                <tr>
                    <td style="width:54%">
                        <h4>Bill To:</h4>
                        <p class="fs12">Name: {{ $request_data->cust_first_name }}</p>
                        <p class="fs12">Address: {{ $request_data->cust_org_address }}</p>
                        <p class="fs12">Mobile: {{ $request_data->cust_mobile_no }}</p>
                    </td>
                    <td style="width:35%">
                        <table>
                            <tr>
                                <td></td>
                                <td>
                                    <h5>&nbsp;</h5>
                                    <p class="fs12">Invoice No:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; INV-{{ $invoice->invoice_number }}</p>
                                    <p class="fs12">Created By:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $created_by }}</p>
                                    <p class="fs12">Created Time:&nbsp; {{ $invoice->created_at }}</p>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>            
            <html-separator/>

            <table border="0" class="default_font_size" style="width: 100%; border-collapse:collapse; line-height:14px;">
                <thead>
                    <tr style="background-color: #EE9321;">
                        <th class="table_border" style="width: 5%; color: white;">S.L.</th>
                        <th class="table_border" style="color: white;">Description</th>
                        <th class="table_border" style="width: 12%; color: white;">Unit/Sft/Kg</th>
                        <th class="table_border" style="width: 10%; color: white;">Qty</th>
                        <th class="table_border" style="width: 11%; color: white;">Unit Price</th>
                        <th class="table_border" style="width: 10%; color: white;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                    @endphp
                    @if (isset($request_data->description))
                        @foreach ($request_data->description as $index => $data)
                        <tr>
                            <td class="table_border" style="text-align: center;">{{ $index+1 }}</td>
                            <td class="table_border" style="text-align: left;">{{$data}}</td>
                            <td class="table_border" style="text-align: right; padding-right: 5px;">
                                {{ $request_data->unit[$index] }}
                            </td>
                            <td class="table_border" style="text-align: right; padding-right: 5px;">
                                {{ $request_data->quantity[$index] }}
                            </td>
                            <td class="table_border" style="text-align: right; padding-right: 5px;">
                                {{ $request_data->price[$index] }}
                            </td>
                            <td class="table_border" style="text-align: right; padding-right: 5px;">
                                {{ $request_data->total_amount[$index] }}
                            </td>
                        </tr>
                        @php
                            $subtotal += intval($request_data->total_amount[$index]);
                        @endphp
                        @endforeach                        
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" rowspan="5" class="">
                            <p>In Words: 
                            @if (isset($request_data->description) && $invoice->total_amount>0)
                                {{ numberToWords($invoice->total_amount) }} Taka Only.
                            @endif
                            </p>
                            <p>Note/Remarks: 
                            @if (isset($request_data->remarks))
                                {{ $request_data->remarks }}                            
                            @endif
                            </p>
                        </td>
                        <td colspan="3" class="" style="text-align: right; padding-right: 5px;">Subtotal</td>
                        <td class="" style="text-align: right; padding-right: 5px;">
                            {{$invoice->subtotal}}
                            {{-- $subtotal --}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="" style="text-align: right; padding-right: 5px;">Discount</td>
                        <td class="" style="text-align: right; padding-right: 5px;">{{ $invoice->discount_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="" style="text-align: right; padding-right: 5px;">Previous Due</td>
                        <td class="" style="text-align: right; padding-right: 5px;">{{ $invoice->previous_due }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="" style="text-align: right; padding-right: 5px;">Paid Amount</td>
                        <td class="" style="text-align: right; padding-right: 5px;">{{ $invoice->paid_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="" style="text-align: right; padding-right: 5px;">Total Amount</td>
                        <td class="" style="text-align: right; padding-right: 5px;">
                            {{-- ($subtotal - $invoice->discount_amount) + $invoice->previous_due - $invoice->paid_amount --}}
                            {{$invoice->total_amount}}
                        </td>
                    </tr>
                </tfoot>
            </table>
            <html-separator/>

            <h5 style="margin: 0; padding: 0;">Payment Information:</h5>
            <table>
                <tr>
                    <td class="default_font_size">Cash/Bank/Check/Bkash/Nagad</td>
                </tr>
                <tr>
                    <td class="default_font_size">Bank Name: </td>
                </tr>
                <tr>
                    <td class="default_font_size">Account No:</td>
                </tr>
                <tr>
                    <td class="default_font_size">Bkash No:</td>
                </tr>
                <tr>
                    <td class="default_font_size">Nagad No:</td>
                </tr>
                <tr>
                    <td class="default_font_size">Mobile No:</td>
                </tr>
            </table>
        </div>
    </body>
</html>