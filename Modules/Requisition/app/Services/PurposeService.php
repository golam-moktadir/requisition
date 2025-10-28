<?php
namespace Modules\Requisition\Services;

use Modules\Requisition\Models\Purpose; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurposeService
{
    public function getDataList()
    {
        return Purpose::get();
    }

    public function saveData($validated)
    {
        $model = new Purpose;
        
        $model->purpose_name = $validated['purpose_name'];
        return $model->save();
    }

    public function getSingleData($id){
        return Purpose::find($id);
    }

    public function updateData($validated, $id){
        $model = Purpose::find($id);
        
        $model->purpose_name = $validated['purpose_name'];
        return $model->save();
    }

    public function deleteData($id){
        $model = Purpose::findOrFail($id);
        return $model->delete();
    }
}
