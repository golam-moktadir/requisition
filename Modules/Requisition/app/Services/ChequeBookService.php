<?php

namespace Modules\Requisition\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Requisition\Models\Cheque;
use Modules\Requisition\Repositories\ChequeBookRepositoryInterface;
use Throwable;

class ChequeBookService
{
    protected $repository;

    public function __construct(ChequeBookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getDataList($request)
    {
        $param = $request->only(['start', 'length', 'draw', 'order', 'columns', 'account_id', 'book_number']);
        return $this->repository->getDataList($param);
    }

    public function getBankAccountList()
    {
        return $this->repository->getBankAccountList();
    }

    public function saveData($validated)
    {
        DB::beginTransaction();
        try {
            $cheque_book = $this->repository->create($validated);
            $this->repository->createCheques($cheque_book->id, $validated);
            DB::commit();
            return true;
        } catch (Throwable $e) {
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
            $this->repository->deleteCheques($id);
            $this->repository->createCheques($id, $validated);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function deleteData($id)
    {
        DB::beginTransaction();

        try {
            $this->repository->deleteCheques($id);
            $this->repository->delete($id);
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
