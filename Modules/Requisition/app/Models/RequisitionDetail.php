<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\RequisitionDetailFactory;

class RequisitionDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'requisition_id',
        'description',
        'amount',
        // add other columns if needed
    ];

    // protected static function newFactory(): RequisitionDetailFactory
    // {
    //     // return RequisitionDetailFactory::new();
    // }
}
