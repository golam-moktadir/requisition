<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\ChequeBookFactory;

class ChequeBook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['account_id', 'book_number', 'start_cheque_no', 'end_cheque_no'];

    public function account()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function cheques()
    {
        return $this->hasMany(Cheque::class);
    }

    // protected static function newFactory(): ChequeBookFactory
    // {
    //     // return ChequeBookFactory::new();
    // }
}
