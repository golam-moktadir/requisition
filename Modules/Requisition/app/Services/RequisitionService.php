<?php
namespace Modules\Requisition\Services;

use Modules\Requisition\Models\Requisition; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RequisitionService
{
    public function getDataList()
    {
        return DB::table('requisitions as r')
                ->leftJoin('companies as c', 'c.id', '=', 'r.company_id')
                ->leftJoin('purposes as p', 'p.id', '=', 'r.purpose_id')
                ->select(['r.id', 'r.description', 'r.requested_to', 'r.amount', 'r.status', 'c.company_name', 'p.purpose_name'])
                ->orderBy('id', 'desc')
                ->get();
    }

    public function saveData($validated, Request $request)
    {

        $model = new Requisition;

        $model->company_id    = $validated['company_id'];
        $model->purpose_id    = $validated['purpose_id'];
        $model->description   = $validated['description'];
        $model->amount        = $validated['amount'];
        $model->requested_to  = $validated['requested_to'];
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
        return Requisition::find($id);
    }

    public function getFiles($id){
        return DB::table('requisition_files')->where('requisition_id', $id)->get();
    }

    public function updateData($validated, Request $request, $id){
        $model = Requisition::find($id);
        
        $model->company_id    = $validated['company_id'];
        $model->purpose_id    = $validated['purpose_id'];
        $model->description   = $validated['description'];
        $model->amount        = $validated['amount'];
        $model->requested_to  = $validated['requested_to'];
                
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

    public function deleteData($id){
        $company = Company::findOrFail($id);

        if ($company->image) {
            Storage::delete('public/companies/' . $company->image);
        }
        return $company->delete();
    }
}
