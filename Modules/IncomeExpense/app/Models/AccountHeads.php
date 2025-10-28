<?php

namespace Modules\IncomeExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\IncomeExpense\Database\Factories\AccountHeadsFactory;

class AccountHeads extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'head_category',
        'account_head_name',
        'parent_id',
        'status',
        'status',
        'created_by'
    ];

    protected static function newFactory(): AccountHeadsFactory
    {
        return AccountHeadsFactory::new();
    }

    // Category can have many children (subcategories)
    public function children()
    {
        return $this->hasMany(AccountHeads::class, 'parent_id');
    }

    // Category can have one parent (another category)
    public function parent()
    {
        return $this->belongsTo(AccountHeads::class, 'parent_id');
    }

}
