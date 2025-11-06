<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Requisition\Models\Requisition;
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
        $data['purposes']  = Purpose::all();
        $data['payees']  = Payee::all();
        return view('requisition::create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'        => 'required',
            'purpose_id'        => 'required|integer',
            'payee_id'          => 'nullable|integer',
            'description'       => 'required|string',
            'amount'            => 'required',
            //'requested_to'      => 'required'
        ]);

        $result = $this->service->saveData($validated, $request);

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
        $data['title']      = 'Requisitions';
        $data['single']     = $this->service->getSingleData($requisition_id); 
        $data['files']      = $this->service->getFiles($requisition_id);
        $data['cheque']     = Cheque::where('requisition_id', $requisition_id)->where('status', 3)->orderBy('id', 'asc')->first();
        $data['approvals']  = Approval::where('requisition_id', $requisition_id)->with('user')->get();
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
        $data['purposes']  = Purpose::all();
        $data['payees']  = Payee::all();
        $data['single'] = $this->service->getSingleData($id); 
        $data['files']  = $this->service->getFiles($id);
        return view('requisition::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'company_id'        => 'required',
            'purpose_id'        => 'required|integer',
            'payee_id'          => 'nullable|integer',
            'description'       => 'required|string',
            'amount'            => 'required',
            //'requested_to'      => 'required'
        ]);

        $result = $this->service->updateData($validated, $request, $id);
        
        if ($result) {
            return back()->with('success', 'Updated Successfully');
        } else {
            return back()->withInput()->with('error', 'Save Failed. Please try again.');
        }
    }

    public function fileDestroy($file_name){
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
            'requisition' => Requisition::whereIn('status', ['pending','rejected'])->findOrFail($requisition_id),
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

    public function issueCheque($id){
        $data['title'] = 'Requisitions';
        $data['single'] = $this->service->getSingleData($id); 
        $data['banks'] = Bank::all();
        return view('requisition::issue-cheque', $data);
    }

    public function getValidChequeList(Request $request){
        $cheques = Cheque::where('bank_id', $request->input('bank_id'))->where('status', 1)->orderBy('id', 'asc')->get();

        $options = "<option value=''>-- Select Cheque Number --</option>";
        foreach ($cheques as $cheque) {
            $options .= "<option value='{$cheque->id}'>{$cheque->cheque_no}</option>";
        }
        return response()->json(['options' => $options]);
    }

    public function updateCheque(Request $request, int $requisition_id){
        $validated = $request->validate([
            'bank_id'   => 'required|integer',
            'cheque_id' => 'required|integer',
        ]);
        $model = Cheque::find($request->input('cheque_id'));
        $model->requisition_id = $requisition_id;
        $model->status         = 3;
        $model->save();

        if($request->hasFile('files')){
            $data = [];
            foreach ($request->file('files') as $key => $file) {
                $fileName = date('YmdHis') . rand() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/cheques', $fileName);

                $data[$key] = [
                    'cheque_id'  => $model->id,
                    'file_name'  => $fileName,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('cheque_files')->insert($data);
        }
        return redirect()->route('requisition.show', ['id' => $requisition_id]);
    }

    public function editIssueCheque($requisition_id){
        $data['title']   = 'Requisitions';
        $data['banks']   = Bank::all();
        $data['single']  = $this->service->getSingleData($requisition_id); 
        $data['cheques'] = Cheque::whereIn('status', [1,3])->get();
        $data['cheque']  = Cheque::where('requisition_id', $requisition_id)->where('status', 3)->first();
        return view('requisition::edit-issue-cheque', $data);
    }

    public function updateIssueCheque(Request $request, int $id){
        $validated = $request->validate([
            'bank_id'   => 'required|integer',
            'cheque_id' => 'required|integer',
        ]);
        $requisition_id = Cheque::where('id', $request->input('cheque_id'))->value('requisition_id');

        if ($requisition_id == 0 || $requisition_id == $id) {
            Cheque::where('requisition_id', $id)
                    ->where('status', 3)
                    ->update(['status' => 1]);

            $model = Cheque::find($request->input('cheque_id'));

            $model->requisition_id = $id;
            $model->status = 3;
            $model->save();

            if($request->hasFile('files')){
                $data = [];
                foreach ($request->file('files') as $key => $file) {
                    $fileName = date('YmdHis') . rand() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/cheques', $fileName);

                    $data[$key] = [
                        'cheque_id'  => $model->id,
                        'file_name'  => $fileName,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('cheque_files')->insert($data);
            }
            return redirect()->route('requisition.show', ['id' => $id])->with('success', 'Issued successfully.');
        }
        else if($requisition_id != $id){
            return redirect()->back()->with('error', 'This Cheque Already Issued.');
        }
    }
}
