<?php

namespace Modules\Requisition\Repositories;

use Modules\Requisition\Models\Bank;

class BankRepository implements BankRepositoryInterface
{
    public function getDataList($param)
    {
        $columns = ['id', 'bank_name'];
        $sort    = $param['order'][0]['column'];
        $order   = $param['order'][0]['dir'];

        $query = Bank::from('banks as b')
            ->select('b.*');
            if ($param['bank_name']) {
                $query->where('bank_name', 'like', '%' . $param['bank_name'] . '%');
            }
        $query->orderBy($columns[$sort], $order);
        $total = Bank::count();
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
        return Bank::with('company')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Bank::create($data);
    }

    public function update(array $data, $id)
    {
        $model = Bank::findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = Bank::findOrFail($id);
        $model->delete();
        return $model;
    }
}
