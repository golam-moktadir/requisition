<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\ChequeFactory;

class Cheque extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): ChequeFactory
    // {
    //     // return ChequeFactory::new();
    // }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
