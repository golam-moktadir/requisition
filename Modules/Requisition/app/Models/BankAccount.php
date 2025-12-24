<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\BankAccountFactory;

class BankAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['bank_id', 'account_holder_name', 'account_number', 'branch_name'];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    // protected static function newFactory(): BankAccountFactory
    // {
    //     // return BankAccountFactory::new();
    // }
}
