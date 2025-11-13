<?php
namespace Modules\Requisition\Services;

use Modules\Requisition\Models\Requisition; 
use Modules\Requisition\Models\Approval; 
use Modules\Requisition\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class RequisitionService
{
    public function getDataList(Request $request)
    {
        return DB::table('requisitions as r')
                ->leftJoin('companies as c', 'c.id', '=', 'r.company_id')
                ->leftJoin('purposes as p', 'p.id', '=', 'r.purpose_id')
                ->select(['r.id', 'r.req_no', 'r.description', 'r.requested_to', 'r.amount', 'r.status', 'c.company_name', 'p.purpose_name'])
                ->when($request->filled('requisition_no'), function ($query) use ($request) {
                    $query->where('r.req_no', 'like', '%' . $request->input('requisition_no') . '%');
                })              
                ->when($request->filled('from_date') && $request->filled('to_date'), function ($query) use ($request) {
                    $query->whereBetween(DB::raw('DATE(r.created_at)'), [$request->input('from_date'), $request->input('to_date')]);
                })
                ->when($request->has('status'), function ($query) use ($request) {
                    $status = $request->input('status');
                    if ($status) {
                        $query->where('r.status', $status);
                    }
                }, function ($query) {
                    $query->where('r.status', 'pending');
                })
                ->orderBy('id', 'desc')
                ->get();
    }

    public function saveData($validated, Request $request)
    {

        $company_name   = Company::where('id', $validated['company_id'])->value('company_name');
        $requisition_no = Requisition::where('company_id', $validated['company_id'])->orderBy('id', 'desc')->value('req_no');
        $prefix         = strtoupper(substr($company_name, 0, 3)).date('ym');
        
        if($requisition_no){
            $number = (int) substr($requisition_no, 7);
            $req_no = $prefix.str_pad($number + 1, 3, '0', STR_PAD_LEFT);
        }else{
            $req_no = $prefix.'001';
        }

        $model = new Requisition;
        $model->req_no        = $req_no;
        $model->company_id    = $validated['company_id'];
        $model->purpose_id    = $validated['purpose_id'];
        $model->payee_id      = $validated['payee_id'];
        $model->description   = $validated['description'];
        $model->amount        = $validated['amount'];
        $model->created_by    = auth()->id();
        
        $model->save();

        if($request->hasFile('files')){
            $data = [];
            foreach ($request->file('files') as $key => $file) {
                $fileName = date('YmdHis') . rand() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/requisitions', $fileName);

                $data[$key] = [
                    'requisition_id' => $model->id,
                    'file_name' => $fileName,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('requisition_files')->insert($data);
        }
        return $model->id;
    }

    public function getSingleData($id){
        return DB::table('requisitions as r')
                ->leftJoin('companies as c', 'c.id', '=', 'r.company_id')
                ->leftJoin('purposes as p', 'p.id', '=', 'r.purpose_id')
                ->leftJoin('payees as ps', 'ps.id', '=', 'r.payee_id')
                ->leftJoin('users as u', 'u.id', '=', 'r.created_by')
                ->select(['r.*', 
                        'c.company_name', 
                        'p.purpose_name', 
                        'ps.payee_name', 
                        'ps.account_holder_name',
                        'u.name as prepared_by',
                        DB::raw("DATE_FORMAT(r.created_at, '%d/%m/%Y') as created_at"),
                        DB::raw("(SELECT COUNT(*) FROM requisition_payments WHERE requisition_id = r.id) as payment_count")
                    ])
                ->where('r.id', $id)
                ->first();
    }

    public function getFiles($id){
        return DB::table('requisition_files')->where('requisition_id', $id)->get();
    }

    public function updateData($validated, Request $request, $id){
        $model = Requisition::find($id);
        
        $model->company_id    = $validated['company_id'];
        $model->purpose_id    = $validated['purpose_id'];
        $model->payee_id      = $validated['payee_id'];
        $model->description   = $validated['description'];
        $model->amount        = $validated['amount'];
        //$model->status        = 'pending';
                
        $model->save();

        if($request->hasFile('files')){
            $data = [];
            foreach ($request->file('files') as $key => $file) {
                $fileName = date('YmdHis') . rand() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/requisitions', $fileName);

                $data[$key] = [
                    'requisition_id' => $model->id,
                    'file_name' => $fileName,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('requisition_files')->insert($data);
        }
        return $model->id;
    }

    public function storeAapproval(int $requisition_id, Request $request){
        DB::beginTransaction();

        try {
            $requisition = Requisition::findOrFail($requisition_id);
            $requisition->status = $request->input('status');
            $requisition->save();

            $approval                   = new Approval();
            $approval->requisition_id   = $requisition_id;
            $approval->status           = $request->input('status');
            $approval->remarks          = $request->input('remarks');
            $approval->user_id          = Auth::id();
            $approval->save();

            DB::commit();
            return true;
        } catch (Exception $e) {                 
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function deleteData($id){
        $company = Company::findOrFail($id);

        if ($company->image) {
            Storage::delete('public/companies/' . $company->image);
        }
        return $company->delete();
    }
}
