<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Requisition\Models\Requisition;
use Modules\Requisition\Models\Approval;
use Modules\Requisition\Models\Company;
use Modules\Requisition\Models\Purpose;
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
        $data['title'] = 'Requisitions';
        $data['requisitions'] = $this->service->getDataList();
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
            'description'       => 'required',
            'amount'            => 'required',
            'requested_to'      => 'required'
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
        return view('requisition::show', [
            'title' => 'Show Requisition',
            'requisition' => Requisition::findOrFail($requisition_id),
            'approvals' => Approval::where('requisition_id', $requisition_id)->with('user')->get(),
        ]);        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    { 
        $data['title'] = 'Requisitions';
        $data['companies'] = Company::all();
        $data['purposes']  = Purpose::all();
        $data['single'] = $this->service->getSingleData($id);// Requisition::whereIn('status', ['pending','rejected'])->findOrFail($id);
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
            'description'       => 'required',
            'amount'            => 'required',
            'requested_to'      => 'required'
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
    public function approval($id)
    {  
        // if( ! in_array(Auth::user()->id, [1])){
        //     abort(404);
        // }
        $data['title']  = 'Requisitions';
        $data['single'] = $this->service->getSingleData($id);
        return view('requisition::approval', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function storeAapproval(int $requisition_id, Request $request)
    {    
        // if( ! in_array(Auth::user()->id, [1])) {
        //     abort(404);
        // }

        $requisition = Requisition::whereIn('status', ['pending','rejected'])->findOrFail($requisition_id);

        if($request->has('status') && in_array($request->status, ['rejected', 'approved'])) {

            DB::beginTransaction();

            try {
                $approval                   = new Approval();
                $approval->requisition_id   = $requisition_id;
                $approval->status           = $request->status;
                $approval->remarks          = $request->remarks;
                $approval->user_id          = Auth::id();
                $approval->save();

                if($approval->id>0) {
                    $requisition->status = $approval->status;
                    $requisition->save();
                }
                else{
                    throw new Exception("Approval update failed.", 500);                    
                }
                
                DB::commit();

            } catch (\Exception $e) {                 
                DB::rollBack();
                about(500);
            }

            return redirect()->route('requisition.show', $requisition_id)
                            ->with('Requisition update successfully!');
        }
        else{
            abort(404);
        }
    }
}
