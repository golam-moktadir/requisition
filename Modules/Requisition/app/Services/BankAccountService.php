<?php

namespace Modules\Requisition\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Requisition\Models\Cheque;
use Modules\Requisition\Repositories\BankAccountRepositoryInterface;
use Exception;

class BankAccountService
{
   protected $repository;

   public function __construct(BankAccountRepositoryInterface $repository)
   {
      $this->repository = $repository;
   }

   public function getDataList($request)
   {
      $param = $request->only(['start', 'length', 'draw', 'order', 'columns', 'account_number', 'bank_id']);
      return $this->repository->getDataList($param);
   }

   public function getBankList()
   {
      return $this->repository->getBankList();
   }

   public function saveData($validated)
   {
      DB::beginTransaction();
      try {
         $this->repository->create($validated);
         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         return false;
      }
   }

   public function getSingleData($id)
   {
      return $this->repository->getSingleData($id);
   }

   public function updateData($validated, $id)
   {
      DB::beginTransaction();
      try {
         $this->repository->update($validated, $id);
         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         return false;
      }
   }

   public function deleteData($id)
   {
      DB::beginTransaction();

      try {
         $this->repository->delete($id);
         DB::commit();
         return true;
      } catch (Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         return false;
      }
   }

   public function saveCheques($validated, $id)
   {

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

   public function getChequeList($id)
   {
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
