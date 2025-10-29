<?php
namespace Modules\Requisition\Services;

use Modules\Requisition\Models\Payee; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PayeeService
{
    public function getDataList()
    {
        return Company::select(
                            'id',
                            'company_name',
                            'phone',
                            'email',
                            'website',
                            'address',
                            DB::raw("CASE 
                                        WHEN status = 1 THEN 'Active' 
                                        WHEN status = 2 THEN 'Inactive'  
                                    END as status_text")
        )->get();

    }

    public function saveData($validated)
    {
        $model = new Payee;
        
        $model->payee_name      = $validated['payee_name'];
        $model->account_holder_name = $validated['account_holder_name'];
        $model->account_number  = $validated['account_number'];
        $model->phone           = $validated['phone'];
        $model->email           = $validated['email'];
        $model->address         = $validated['address'];

        return $model->save();
    }

    public function getSingleData($id){
        return Company::find($id);
    }

    public function updateData($validated, Request $request, $id){
        $company = Company::find($id);
        
        $company->company_name = $validated['company_name'];
        $company->phone        = $validated['phone'];
        $company->email        = $validated['email'];
        $company->website      = $validated['website'];
        $company->address      = $validated['address'];
        $company->status       = $validated['status'];

        if ($request->hasFile('image')) {

            if ($company->image) {
                Storage::delete('public/companies/' . $company->image);
            }
            
            $file = $request->file('image');
            $fileName = date('YmdHis').rand().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/companies', $fileName);
            $company->image = $fileName;
        }

        return $company->save();
    }

    public function deleteData($id){
        $company = Company::findOrFail($id);

        if ($company->image) {
            Storage::delete('public/companies/' . $company->image);
        }
        return $company->delete();
    }
}
