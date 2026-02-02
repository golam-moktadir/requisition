<?php

namespace Modules\Requisition\Repositories;

use Modules\Requisition\Models\ChequeBook;
use Modules\Requisition\Models\BankAccount;
use Modules\Requisition\Models\Cheque;
use Illuminate\Support\Facades\DB;

class ChequeBookRepository implements ChequeBookRepositoryInterface
{
    public function getDataList($param)
    {
        $columns = ['cb.id'];
        $sort    = $param['order'][0]['column'];
        $order   = $param['order'][0]['dir'];

        $query = ChequeBook::from('cheque_books as cb')
            ->join('bank_accounts as ba', 'ba.id', '=', 'cb.account_id')
            ->join('banks as b', 'b.id', '=', 'ba.bank_id')
            ->select(
                'cb.*',
                DB::raw("CONCAT_WS(' - ', ba.account_number, b.bank_name) AS account_no")
            )
            ->when($param['account_id'] ?? null, fn($q, $v) => $q->where('cb.account_id', $v))
            ->when($param['book_number'] ?? null, fn($q, $v) => $q->where('cb.book_number', $v))
            ->orderBy($columns[$sort], $order);
        $filtered = $query->count();
        $total = ChequeBook::count();

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
