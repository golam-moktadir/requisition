<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\OrganizationSetting;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoicePdfController extends Controller
{
    function allInvoice(Request $request) {

        $invoice = Invoice::with('shop')->orderBy('invoice_id', 'DESC')->limit(200);

        // $invoice->where('invoice_id', '>=', 20);

        if($request->get('shop_id')>0){
            $invoice->where('shop_id', $request->get('shop_id'));
        }
        if($request->get('cust_id')>0){
            $invoice->where('cust_id', $request->get('cust_id'));
        }
        if($request->get('start_date')!=''){
            $invoice->whereDate('created_at', '>=', $request->get('start_date'));
        }        
        if($request->get('end_date')!=''){
            $invoice->whereDate('created_at', '<=', $request->get('end_date'));
        }     
        if($request->get('invoice_number')!=null && $request->get('invoice_number')>=0){
            $invoice->where('invoice_number', '=', $request->get('invoice_number'));
        }        

        return view('admin/invoice-pdf/all_invoice2', [
            'title'         => 'Invoice',
            'title_sub'     => 'Search Invoice',
            'invoices'      => $invoice->get(),
            'orgs'          => OrganizationSetting::all(),
            'shop_id'       => $request->get('shop_id'),
            'cust_id'       => $request->get('cust_id'),
            'start_date'    => $request->get('start_date'),
            'end_date'      => $request->get('end_date'),
            'invoice_number'=> $request->get('invoice_number'),
            'customers'     => Customer::orderBy('org_name', 'ASC')->get(),
        ]);         
    }    

    function generateInvoice(Request $request) {
        $data = [
            'title' => 'Invoice',
            'title_sub' => 'Invoice',
            'orgs' => OrganizationSetting::all(),
            'customers' => Customer::orderBy('first_name', 'ASC')->get(),
        ];

        return view('admin/invoice-pdf/generate-invoice', $data);
    }

    function saveInvoice(Request $request){
            
        $request_data = $request->toArray();
        unset($request_data['_token']);
        
        $invoice_object = Invoice::create([
            'invoice_number' => time() . rand('100001', '9999999'),
            'shop_id' => $request->get('shop_id'),
            'cust_id' => $request->get('cust_id'),
            'subtotal' => array_sum($request_data['total_amount']), // intval($request->get('subtotal')),
            'discount_amount' => intval($request->get('discount')),
            'previous_due' => intval($request->get('prev_due_amount')),
            'paid_amount' => intval($request->get('paid_amount')),
            'total_amount' => ( array_sum($request_data['total_amount']) + intval($request->get('prev_due_amount')) - intval($request->get('discount')) - intval($request->get('paid_amount')) ),
            'request_data' => json_encode($request_data),
            'created_by' => Auth::id(),
        ]);

        $invoice_number = date('Ym', strtotime($invoice_object->created_at)) . $request->get('cust_id') . $invoice_object->invoice_id;
        $invoice_object->invoice_number = $invoice_number;
        $invoice_object->save();  
        
        return redirect()->route('admin.invoice2.showPdf', [$invoice_object->invoice_id]);
    }

    function showPdf(Request $request, $invoiceId) {

        $invoice = Invoice::where('invoice_id', $invoiceId)->firstOrFail();
        $organizationSetting = OrganizationSetting::where('id', $invoice->shop_id)->firstOrFail();
        $created_by = User::where('id', $invoice->created_by)->firstOrFail();
          
        $imagePath = asset('custom-assets/logo/logo1.jpeg'); // Adjust the path as needed
        $imageData = base64_encode(file_get_contents($imagePath));
                        
        $data = [
            'foo' => 'foo',
            'bar' => 'var',
            'invoice' => $invoice,
            'request_data' => json_decode($invoice->request_data, false),
            'imageData' => $imageData,
            'printed_by' => Auth::user()->name,
            'printed_time' => gmdate('Y-m-d H:i:s A', strtotime('+360 minutes')),
            'organizationSetting' => $organizationSetting,
            'created_by' => $created_by->name
            
        ];

        $pdf = PDF::chunkLoadView('<html-separator/>', 'admin.invoice-pdf.show-pdf', $data);
        return $pdf->stream('Invoice-' . $invoice->invoice_number . '.pdf');
    }

    function deleteInvoice($invoiceId) {        
        Invoice::where('invoice_id', $invoiceId)->limit(1)->delete();
        return redirect()
                ->route('admin.invoice2.allInvoice')
                ->with('warning', 'Data has been deleted successfully!');
    }
}
