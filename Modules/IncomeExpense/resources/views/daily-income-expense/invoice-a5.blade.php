@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.daily_transactions') }}">Daily Transactions</a>
</li>
<li class="breadcrumb-item">
    invoice
</li>
@endsection

@section('pagecss')
<style>
    /* Regular page styles */
    body {
        font-family: Arial, sans-serif;
    }

    /* Print-specific styles */
    @media print {
        /* Set the page size to A5 (148mm x 210mm) */
        @page {
            size: A5;
            margin: 2mm; /* Set margins for the A5 page */
        }

        /* Adjust font size and layout for printing */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;  /* Set default font size for printing */
            margin: 0;
            padding: 0;
        }

        .col-11 {
            font-size: 14px; /* Font size for the voucher content */
        }

        /* Adjust font size for table headers */
        table th {
            font-size: 16px;
        }

        /* Adjust font size for table data */
        table td {
            font-size: 14px;
        }

        /* Ensure that background colors are printed */
        body {
            -webkit-print-color-adjust: exact; /* For Chrome/Safari */
            print-color-adjust: exact; /* For Firefox */
        }

        /* Adjust table width to fit in the A5 page */
        table {
            width: 100%;
            table-layout: fixed; /* Ensure the table fits the page */
        }

        /* Remove any unnecessary padding or margins for printing */
        table td, table th {
            padding: 5px;
        }
    }
</style>
@endsection    

@section('content-body')        
    <div class="mt-2 p-3 card">
        @include('admin.layouts.message')                
        <div class="col-11" id="voucher-to-print">
            <table style="width: 100%; border-collapse: collapse; text-align: left; padding-left: 3px;">
                <tr><td colspan="4" style="text-align: center">Logo</td></tr>                    
                <tr><td colspan="4" style="text-align: center">Address</td></tr>       
                <tr style="border-top: 1.5px solid;">
                    <td colspan="4">
                        <div style="padding-left: 15px; margin: 10px 0 10px 0;">
                            <b>Voucher No#</b> {{ $invoice->id }} 
                            <br>
                            <b>Debited To#</b> {{ $invoice->debited_to }} 
                            <br>
                            <b>Date#</b> {{ $invoice->created_at }} 
                        </div>
                    </td>
                </tr>
                <tr style="background: #B2B2B2; color: #fff; border-left: 1.5px solid #B2B2B2; border-right: 1.5px solid #B2B2B2;">
                    <th style="width: 75%; padding-left: 15px; text-align: center; border-right: 1.5px solid;">
                        Particulars
                    </th>
                    <th style="text-align: center">
                        Taka
                    </th>
                </tr>
                <tr style="border-left: 1.5px solid #B2B2B2; border-right: 1.5px solid #B2B2B2;">
                    <td style="width: 75%; padding-left: 15px; text-align: left; border-right: 1.5px solid #B2B2B2;">
                        <div style="min-height: 100px;">
                            <b>{{ $invoice->account_head->account_head_name }}</b>
                            <br>
                            {{ $invoice->remarks }} 
                        </div>
                    </td>
                    <td style="text-align: right; padding-right: 5px;">
                        <div style="min-height: 100px;">
                        {{ $invoice->amount }} 
                        </div>
                    </td>
                </tr>
                <tr style="background: #B2B2B2; color: #fff; border-left: 1.5px solid #B2B2B2; border-right: 1.5px solid #B2B2B2;">
                    <td style="width: 75%; padding-right: 15px; text-align: right; border-right: 1.5px solid;">                        
                        Total#                        
                    </td>
                    <td style="text-align: right; padding-right: 5px;">
                        {{ $invoice->amount }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="width: 75%; padding-left: 15px; text-align: left;">                        
                        <b>Amount in word#</b> {{ numberToWords($invoice->amount) }}                   
                    </td>
                </tr>
            </table>
            <table style="width: 100%; border-collapse: collapse; text-align: left; padding-left: 3px;">
                <tr>
                    <td colspan="3">                        
                        <br>
                        <br>                
                        <br>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%; text-align: center;">                        
                        .........................
                        <br>
                        Authority
                    </td>
                    <td style="width: 33%; text-align: center;">                        
                    {{ $invoice->user->name }}
                        <br>
                        .........................
                        <br>
                        Accountant                        
                    </td>
                    <td style="text-align: center;">                        
                        .........................
                        <br>
                        Receiver's Signature                        
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center">                        
                        <br>
                        <span>
                            Printed By {{ auth()->user()->name }} at {{ gmdate('Y-m-d H:i:s', strtotime('+6 hours')) }}
                        </span>
                        <br>
                    </td>
                </tr>                
            </table>
            <br>
            <br>
            <br>
            <br>
        </div>
        <button onclick="printDiv()">
            Print Voucher
        </button>
    </div>
@endsection

@section('footerjs')

<script>
    function printDiv() {
            // Get the content of the div to print
            var content = document.getElementById('voucher-to-print').innerHTML;

            // Open a new window or tab
            var printWindow = window.open('', '', 'height=600,width=800');

            // Write the content to the new window
            printWindow.document.write('<html><head><title>Voucher-No-{{ $invoice->id }}</title><style>@media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; font-family: Arial, sans-serif; font-size: 12px; } @page { size: A5; margin: 2mm; } table th { font-size: 16px; } table td { font-size: 14px; } }</style></head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');

            // Close the document stream to trigger the print dialog
            printWindow.document.close();

            // Trigger the print dialog
            printWindow.print();

            // Close the window after a short delay (to allow the print dialog to appear)
            setTimeout(function() {
                printWindow.close();
            }, 1000); // Adjust delay as needed (1000ms = 1 second)
        }    
</script>
@endsection
