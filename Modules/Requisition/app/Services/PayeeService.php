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
        return Payee::orderBy('id', 'desc')->get();
    }

    public function saveData($validated)
    {
        $model = new Payee;
        
        $model->payee_name          = $validated['payee_name'];
        $model->account_holder_name = $validated['account_holder_name'];
        $model->account_number      = $validated['account_number'];
        $model->phone               = $validated['phone'];
        $model->email               = $validated['email'];
        $model->address             = $validated['address'];

        return $model->save();
    }

    public function getSingleData($id){
        return Payee::find($id);
    }

    public function updateData($validated, $id){
        $model = Payee::find($id);
        
        $model->payee_name          = $validated['payee_name'];
        $model->account_holder_name = $validated['account_holder_name'];
        $model->account_number      = $validated['account_number'];
        $model->phone               = $validated['phone'];
        $model->email               = $validated['email'];
        $model->address             = $validated['address'];

        return $model->save();
    }

    public function deleteData($id){
        $model = Payee::findOrFail($id);
        return $model->delete();
    }
}
