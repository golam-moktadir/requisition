<?php

namespace Modules\IncomeExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\IncomeExpense\Database\Factories\DailyIncomeExpenseFactory;
use  Modules\IncomeExpense\Models\AccountHeads;
use App\Models\User;

class DailyIncomeExpense extends Model
{
    use HasFactory;

    protected $table = 'daily_income_expense';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'account_head_id',
        'amount',
        'remarks',
        'created_by',
        'debited_to',
    ];

    public function account_head()
    {
        return $this->belongsTo(AccountHeads::class, 'account_head_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // protected static function newFactory(): DailyIncomeExpenseFactory
    // {
    //     // return DailyIncomeExpenseFactory::new();
    // }
}
