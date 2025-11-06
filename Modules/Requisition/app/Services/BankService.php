<?php
namespace Modules\Requisition\Services;

use Modules\Requisition\Models\Bank; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Modules\Requisition\Models\Cheque;

class BankService
{
    public function getDataList()
    {
        return DB::table('banks as b')
            ->leftJoin('companies as c', 'c.id', '=', 'b.company_id')
            ->select(['b.*', 'c.company_name'])
            ->orderBy('b.id', 'desc')
            ->get();
    }

    public function saveData($validated)
    {
        $model = new Bank;

        $model->company_id          = $validated['company_id'];        
        $model->bank_name           = $validated['bank_name'];
        $model->account_holder_name = $validated['account_holder_name'];
        $model->account_no          = $validated['account_no'];
        $model->account_type        = $validated['account_type'];
        $model->branch_name         = $validated['branch_name'];
        $model->branch_address      = $validated['branch_address'];

        return $model->save();
    }

    public function getSingleData($id){
        return Bank::with('company')->find($id);
        //return Bank::find($id);
    }

    public function updateData($validated, $id){
        $model = Bank::find($id);
        
        $model->company_id          = $validated['company_id'];        
        $model->bank_name           = $validated['bank_name'];
        $model->account_holder_name = $validated['account_holder_name'];
        $model->account_no          = $validated['account_no'];
        $model->account_type        = $validated['account_type'];
        $model->branch_name         = $validated['branch_name'];
        $model->branch_address      = $validated['branch_address'];

        return $model->save();
    }

    public function saveCheques($validated, $id){
        
        $start = $validated['start_no'];
        $end   = $validated['end_no'];

        $cheques = [];
        for ($index = $start; $index <= $end; $index++) {
            $cheques[] = [
                'bank_id'        => $id,
                'cheque_no'      => $index,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }
        return Cheque::insert($cheques);
    }

    public function getChequeList($id){
        return Cheque::where('bank_id', $id)
                ->select('id', 'cheque_no', 'status', 'remarks', 'bank_id')
                ->selectRaw("CASE 
                            WHEN status = 1 THEN 'Active'
                            WHEN status = 2 THEN 'Inactive'
                            WHEN status = 3 THEN 'Used'
                            ELSE 'Unknown'
                            END as status_text")
                ->get();
    }
}
