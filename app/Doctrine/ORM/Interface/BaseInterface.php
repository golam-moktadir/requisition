<?php 

namespace App\Doctrine\ORM\Interface;

interface BaseInterface {
    public function all();
    public function findOne($id);
}