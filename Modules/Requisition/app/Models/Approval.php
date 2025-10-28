<?php

namespace Modules\Requisition\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Requisition\Database\Factories\ApprovalFactory;
use App\Models\User;

class Approval extends Model
{
    use HasFactory;

    public $table = 'requisition_approval';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'requisition_id',
        'status',
        'remarks',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    // protected static function newFactory(): ApprovalFactory
    // {
    //     // return ApprovalFactory::new();
    // }
}
