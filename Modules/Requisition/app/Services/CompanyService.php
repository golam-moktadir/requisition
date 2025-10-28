<?php
namespace Modules\Requisition\Services;

use Modules\Requisition\Models\Company; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyService
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

    public function saveData($validated, Request $request)
    {
        $company = new Company;
        
        $company->company_name = $validated['company_name'];
        $company->phone        = $validated['phone'];
        $company->email        = $validated['email'];
        $company->website      = $validated['website'];
        $company->address      = $validated['address'];
        $company->status       = $validated['status'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            //$fileName = time().rand().'-'.$file->getClientOriginalName();
            $fileName = date('YmdHis').rand().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/companies', $fileName); 
            $company->image = $fileName;
        }
        return $company->save();
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
