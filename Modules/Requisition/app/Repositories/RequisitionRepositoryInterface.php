<?php

namespace Modules\Requisition\Repositories;

interface RequisitionRepositoryInterface
{
    public function getDataList(array $param);
    public function getBankAccountList();
    public function getSingleData($id);
    public function create(array $data);
    public function deleteCheques(int $id);
    public function createCheques(int $id, array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
}
