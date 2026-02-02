<?php

namespace Modules\Requisition\Repositories;

use Modules\Requisition\Models\ChequeBook;
use Modules\Requisition\Models\BankAccount;
use Modules\Requisition\Models\Cheque;
use Illuminate\Support\Facades\DB;
use Modules\Requisition\Models\Requisition;

class RequisitionRepository implements RequisitionRepositoryInterface
{
   public function getDataList(array $param)
   {
      $columns = ['r.id', 'r.req_no', 'r.created_at', 'c.company_name', 'p.purpose_name', '', 'r.status'];
      $sort    = $param['order'][0]['column'] ?? 0;
      $order   = $param['order'][0]['dir'] ?? 'desc';

      $query = Requisition::from('requisitions as r')
         ->leftJoin('companies as c', 'c.id', '=', 'r.company_id')
         ->leftJoin('purposes as p', 'p.id', '=', 'r.purpose_id')
         ->select([
            'r.id',
            'r.req_no',
            'r.status',
            'c.company_name',
            'p.purpose_name',
            DB::raw("DATE_FORMAT(r.created_at, '%d/%m/%Y') as created_date"),
            DB::raw('(SELECT SUM(amount) FROM requisition_details WHERE requisition_id = r.id) as total_amount')
         ])
         ->when($param['requisition_no'] ?? null, fn($q, $v) => $q->where('r.req_no', 'like', '%' . $v . '%'))
         ->when($param['company_id'] ?? null, fn($q, $v) => $q->where('r.company_id', $v))
         ->when(($param['from_date'] ?? null) && ($param['to_date'] ?? null),
                  fn($q) => $q->whereBetween(DB::raw('DATE(r.created_at)'), [$param['from_date'], $param['to_date']])
         )
         ->orderBy($columns[$sort], $order);

      $filtered = $query->count();
      $total = Requisition::count();

      $start = $param['start'] ?? 0;
      $length = $param['length'] ?? 10;

      $data = $query->offset($start)->limit($length)->get();

      return [
         'draw' => $param['draw'] ?? 1,
         'recordsTotal' => $total,
         'recordsFiltered' => $filtered,
         'data' => $data,
      ];
   }

   public function getBankAccountList()
   {
      return BankAccount::from('bank_accounts as ba')
         ->join('banks as b', 'b.id', '=', 'ba.bank_id')
         ->select(
            'ba.id',
            DB::raw("CONCAT_WS(' - ', ba.account_number, b.bank_name) AS account_no")
         )
         ->get();
   }

   public function getSingleData($id)
   {
      //return ChequeBook::findOrFail($id);
      return ChequeBook::with(['account.bank', 'cheques'])->findOrFail($id);
   }

   public function create(array $data)
   {
      return ChequeBook::create($data);
   }

   public function createCheques(int $id, array $data)
   {
      $rows = [];
      for ($cheque_no = $data['start_cheque_no']; $cheque_no <= $data['end_cheque_no']; $cheque_no++) {
         $rows[] = [
            'cheque_book_id' => $id,
            'cheque_no'      => $cheque_no,
            'created_at'     => now(),
            'updated_at'     => now(),
         ];
      }
      return Cheque::insert($rows);
   }

   public function update(array $data, int $id)
   {
      $model = ChequeBook::findOrFail($id);
      $model->update($data);
      return $model;
   }

   public function deleteCheques(int $id)
   {
      return Cheque::where('cheque_book_id', $id)->delete();
   }

   public function delete(int $id)
   {
      $model = ChequeBook::findOrFail($id);
      $model->delete();
      return $model;
   }
}
