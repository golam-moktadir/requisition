<?php

namespace Modules\Requisition\Repositories;

interface BankAccountRepositoryInterface
{
   public function getDataList(array $param);
   public function getSingleData($id);
   public function create(array $data);
   public function update(array $data, $id);
   public function delete($id);
   public function getBankList();
}
