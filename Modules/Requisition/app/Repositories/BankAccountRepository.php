<?php

namespace Modules\Requisition\Repositories;

use Modules\Requisition\Models\Bank;
use Modules\Requisition\Models\BankAccount;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    public function getDataList($param)
    {
        $columns = ['id'];
        $sort    = $param['order'][0]['column'];
        $order   = $param['order'][0]['dir'];

        $query = BankAccount::from('bank_accounts as ba')
            ->join('banks as b', 'b.id', '=', 'ba.bank_id')
            ->select('ba.*', 'b.bank_name');

        // if ($param['bank_name']) {
        //     $query->where('bank_name', 'like', '%' . $param['bank_name'] . '%');
        // }
        $query->orderBy($columns[$sort], $order);
        $total = BankAccount::count();
        $filtered = $query->count();

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

    public function getSingleData($id)
    {
        return BankAccount::with('bank')->findOrFail($id);
    }

    public function create(array $data)
    {
        return BankAccount::create($data);
    }

    public function update(array $data, $id)
    {
        $model = BankAccount::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = BankAccount::findOrFail($id);
        $model->delete();
        return $model;
    }

    public function getBankList()
    {
        return Bank::all();
    }
}
