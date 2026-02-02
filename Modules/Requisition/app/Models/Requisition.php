<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\RequisitionFactory;

class Requisition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title', 
        'description', 
        'requested_to', 
        'amount', 
        'transaction_mode', 
        'bank_check_info', 
        'status', 
        'created_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createRequisition(array $data)
    {
        
    }
    
    // protected static function newFactory(): RequisitionFactory
    // {
    //     // return RequisitionFactory::new();
    // }

    // public function items()
    // {
    //     return $this->hasMany(Item::class);
    // }

}


