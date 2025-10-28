<?php 

namespace App\Doctrine\ORM\Repository;

use App\Doctrine\ORM\Entity\AccountHeads;
use Doctrine\ORM\EntityRepository;
use App\Doctrine\ORM\Interface\BaseInterface;

class AccountHeadsRepository extends EntityRepository implements BaseInterface{
    public function all(){

        return [];
    }
    public function findOne($id){

        return $id;
    }
}

