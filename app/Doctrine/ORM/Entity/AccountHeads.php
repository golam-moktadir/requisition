<?php 

namespace App\Doctrine\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "App\Doctrine\ORM\Repository\AccountHeadsRepository")]
 #[ORM\Table(name: "account_heads")]
 class AccountHeads
 {
     #[ORM\Id]
     #[ORM\Column(type: "integer")]
     #[ORM\GeneratedValue(strategy: "AUTO")]
     private $id;

     #[ORM\Column(type: "string", nullable: false)]
     private $account_head;
    
    #[ORM\Column(type:"datetime", nullable:true)]
    protected $created_at;

    #[ORM\Column(type:"datetime", nullable:true, options:['default'=>'CURRENT_TIMESTAMP'])]
    protected $updated_at;    


     /**
      * Get the value of id
      */ 
     public function getId()
     {
          return $this->id;
     }

     /**
      * Set the value of id
      *
      * @return  self
      */ 
     public function setId($id)
     {
          $this->id = $id;

          return $this;
     }

     /**
      * Get the value of account_head
      */ 
     public function getAccountHead()
     {
          return $this->account_head;
     }

     /**
      * Set the value of account_head
      *
      * @return  self
      */ 
     public function setAccountHead($account_head)
     {
          $this->account_head = $account_head;

          return $this;
     }

    /**
     * Get the value of created_at
     *
     * @return  \DateTime
     */ 
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     *
     * @param  \DateTime  $created_at
     *
     * @return  self
     */ 
    public function setCreatedAt(\DateTime $created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     *
     * @return  \DateTime
     */ 
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     *
     * @param  \DateTime  $updated_at
     *
     * @return  self
     */ 
    public function setUpdatedAt(\DateTime $updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }
 }
