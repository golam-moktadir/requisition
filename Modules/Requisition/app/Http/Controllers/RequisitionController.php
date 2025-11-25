<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Requisition\Models\Requisition;
use Modules\Requisition\Models\RequisitionPayment;
use Modules\Requisition\Models\Approval;
use Modules\Requisition\Models\Company;
use Modules\Requisition\Models\Purpose;
use Modules\Requisition\Models\Payee;
use Modules\Requisition\Models\Bank;
use Modules\Requisition\Models\Cheque;
use Modules\Requisition\Services\RequisitionService;
use Illuminate\Support\Facades\Storage;

class RequisitionController extends Controller
{
    protected $service;

    public function __construct(RequisitionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd();
        $data['title'] = 'Requisitions';
        $data['companies'] = Company::all();
        $data['requisitions'] = $this->service->getDataList($request);
        return view('requisition::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Requisitions';
        $data['companies'] = Company::all();
        $data['purposes'] = Purpose::all();
        $data['payees'] = Payee::all();
        return view('requisition::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'company_id' => 'required',
            'purpose_id' => 'required|integer',
            'payee_id' => 'nullable|integer',
            'description' => 'required|array',
            'description.*' => 'required|string|max:255',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1',
        ]);

        $result = $this->service->saveData($validated, $request);
        //dd($result);
        if ($result) {
            return redirect()->route('requisition.index')->with('success', 'Created Successfully');
        } else {
            return back()->withInput()->with('error', 'Failed to create City. Please try again.');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(int $requisition_id)
    {
        $data['title'] = 'Requisitions';
        $data['single'] = $this->service->getSingleData($requisition_id);
        $data['details'] = $this->service->getMultipleData($requisition_id);
        $data['files'] = $this->service->getFiles($requisition_id);
        $data['payment'] = RequisitionPayment::with('cheque')->where('requisition_id', $requisition_id)->orderBy('id', 'asc')->first();
        $data['approvals'] = Approval::where('requisition_id', $requisition_id)->with('user')->get();
        $data['approved'] = Approval::with('user')->where('requisition_id', $requisition_id)->where('status', 'approved')->first();
        //dd($data);
        return view('requisition::show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['title'] = 'Requisitions';
        $data['companies'] = Company::all();
        $data['purposes'] = Purpose::all();
        $data['payees'] = Payee::all();
        $data['single'] = $this->service->getSingleData($id);
        $data['details'] = $this->service->getMultipleData($id);
        $data['files'] = $this->service->getFiles($id);
        return view('requisition::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $validated = $request->validate([
            'company_id' => 'required',
            'purpose_id' => 'required|integer',
            'payee_id' => 'nullable|integer',
            'description' => 'required|array',
            'description.*' => 'required|string|max:255',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:1'
        ]);

        $result = $this->service->updateData($validated, $request, $id);

        if ($result) {
            return back()->with('success', 'Updated Successfully');
        } else {
            return back()->withInput()->with('error', 'Save Failed. Please try again.');
        }
    }

    public function fileDestroy($file_name)
    {
        DB::table('requisition_files')->where('file_name', $file_name)->delete();
        Storage::delete('public/requisitions/' . $file_name);
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function approval($requisition_id)
    {
        // if( ! in_array(Auth::user()->id, [1])){
        //     abort(404);
        // }
        return view('requisition::approval', [
            'title' => 'Approve Requisition',
            'requisition' => Requisition::whereIn('status', ['pending', 'rejected'])->findOrFail($requisition_id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function storeAapproval(int $requisition_id, Request $request)
    {
        // dd($request->all());
        // if( ! in_array(Auth::user()->id, [1])) {
        //     abort(404);
        // }
        $result = $this->service->storeAapproval($requisition_id, $request);

        if ($result) {
            return redirect()->route('requisition.index')->with('success', 'Requisition Approved Successfully');
        } else {
            return back()->with('error', 'Requisition Update Failed');
        }
    }

    public function addPayment($id)
    {
        $data['title'] = 'Requisitions';
        $data['single'] = $this->service->getSingleData($id);
        $data['banks'] = Bank::all();
        return view('requisition::add-payment', $data);
    }

    public function getValidChequeList(Request $request)
    {
        $cheques = Cheque::where('bank_id', $request->input('bank_id'))->where('status', 1)->orderBy('id', 'asc')->get();

        $options = "<option value=''>-- Select Cheque Number --</option>";
        foreach ($cheques as $cheque) {
            $options .= "<option value='{$cheque->id}'>{$cheque->cheque_no}</option>";
        }
        return response()->json(['options' => $options]);
    }

    public function savePayment(Request $request)
    {

        $rules = [
            'payment_type' => ['required', 'in:1,2'],
        ];

        if ($request->payment_type == 1) {
            $rules['bank_id'] = ['required', 'integer'];
            $rules['cheque_id'] = ['required', 'integer'];
        }

        if (in_array($request->payment_type, [2, 3])) {
            $rules['cash_description'] = ['nullable', 'string', 'max:255'];
        }

        $rules['files.*'] = ['nullable', 'file', 'mimes:pdf,jpg,png,docx', 'max:2048'];

        $validated = $request->validate($rules);

        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $path = $file->store('payments', 'public');
                // $files[] = basename($path);
                $filename = basename($path);
                $title = $request->title[$index] ?? null;
                $files[] = [
                    'name' => $filename,
                    'title' => $title
                ];
            }
        }

        $payment = new RequisitionPayment();
        $payment->requisition_id = $request->requisition_id;
        $payment->payment_type = $request->payment_type;

        if ($request->payment_type == 1) {
            $payment->cheque_id = $request->cheque_id;
            $payment->cash_description = null;
        } else {
            $payment->cheque_id = null;
            $payment->cash_description = $request->cash_description;
        }

        $payment->files = empty($files) ? null : json_encode($files);
        $payment->save();

        $requisition = Requisition::findOrFail($request->requisition_id);
        $requisition->status = 'issued';
        $requisition->save();

        $approval = new Approval();
        $approval->requisition_id = $request->requisition_id;
        $approval->status = 'issued';
        $approval->user_id = Auth::id();
        $approval->save();

        return redirect()->route('requisition.show', ['id' => $request->requisition_id]);
    }

    public function editPayment($requisition_id)
    {
        $data['title'] = 'Requisitions';
        $data['banks'] = Bank::all();
        $data['cheques'] = Cheque::where('status', 1)->get();
        $data['payment'] = RequisitionPayment::with(['requisition.company', 'cheque'])->where('requisition_id', $requisition_id)->first();
        return view('requisition::edit-payment', $data);
    }

    public function updatePayment(Request $request, int $id)
    {
        $rules = [
            'payment_type' => ['required', 'in:1,2'],
        ];

        if ($request->payment_type == 1) {
            $rules['bank_id'] = ['required', 'integer'];
            $rules['cheque_id'] = ['required', 'integer'];
        }

        if (in_array($request->payment_type, [2, 3])) {
            $rules['cash_description'] = ['nullable', 'string', 'max:255'];
        }

        $rules['files.*'] = ['nullable', 'file', 'mimes:pdf,jpg,png,docx', 'max:2048'];
        $validated = $request->validate($rules);

        $newFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('payments', 'public');
                $newFiles[] = basename($path);
            }
        }
        $payment = RequisitionPayment::find($id);

        $existingFiles = json_decode($payment->files, true) ?? [];

        $finalFiles = $newFiles ? array_merge($existingFiles, $newFiles) : $existingFiles;

        $payment->payment_type = $request->payment_type;
        if ($request->payment_type == 1) {
            $payment->cheque_id = $request->cheque_id;
            $payment->cash_description = null;
        } else {
            $payment->cheque_id = null;
            $payment->cash_description = $request->cash_description;
        }
        $payment->files = empty($finalFiles) ? null : json_encode($finalFiles);
        $payment->save();

        $requisition = Requisition::findOrFail($request->requisition_id);
        $requisition->status = 'issued';
        $requisition->save();

        $approval = new Approval();
        $approval->requisition_id = $request->requisition_id;
        $approval->status = 'issued';
        $approval->user_id = Auth::id();
        $approval->save();
        return redirect()->route('requisition.show', ['id' => $request->requisition_id])->with('success', 'Issued successfully.');
    }

    public function requisitionFileDestroy($id, $file)
    {
        //return response()->json($id);

        $payment = RequisitionPayment::findOrFail($id);
        $existingFiles = json_decode($payment->files, true) ?? [];

        $updatedFiles = array_filter($existingFiles, function ($item) use ($file) {
            return $item !== $file;
        });

        if (Storage::disk('public')->exists('payments/' . $file)) {
            Storage::disk('public')->delete('payments/' . $file);
        }

        $payment->files = empty($updatedFiles) ? null : json_encode(array_values($updatedFiles));
        $payment->save();
        return response()->json(['success' => true]);
    }
}
