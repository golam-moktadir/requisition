<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\RequisitionPaymentFactory;

class RequisitionPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'requisition_id',
        'payment_type',
        'cheque_id',
        'cash_amount',
        'cash_description',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
    ];

    // Relationships
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function cheque()
    {
        return $this->belongsTo(Cheque::class);
    }

    public function getPaymentTypeTextAttribute()
    {
        return $this->payment_type == 1 ? 'Cheque' : 'Cash';
    }

    // protected static function newFactory(): RequisitionPaymentFactory
    // {
    //     // return RequisitionPaymentFactory::new();
    // }
}
