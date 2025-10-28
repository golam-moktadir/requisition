<?php 
namespace Modules\IncomeExpense\Repositories;

use App\Repositories\BaseRepository;
use Modules\IncomeExpense\Models\DailyIncomeExpense;
use Illuminate\Database\Eloquent\Collection;


class DailyIncomeExpenseRepository extends BaseRepository
{
    /**
     * BaseRepository constructor.
     * 
     * @param  Model  $model
     */
    public function __construct(DailyIncomeExpense $model)
    {
        $this->model = $model;
    } 

    public static function getAllByDate(string $date): Collection
    {
        return DailyIncomeExpense::where('created_at', 'like', date('Y-m-d', strtotime(trim($date))) . "%")
                ->get();   
    }

    public static function getAllByDateAndAccHead(string $date, int $account_head=0): Collection
    {
        return DailyIncomeExpense::where('created_at', 'like', date('Y-m-d', strtotime(trim($date))) . "%")
                ->where(function($query) use($account_head){
                    if($account_head>0){
                        $query->where('account_head_id', $account_head);
                    }
                })
                ->with('account_head', 'user')
                ->get();   
    }

    public function delete(int $id): bool
    {
        $date = date('Y-m-d');
        return DailyIncomeExpense::where('id', $id)
            ->where('created_at', 'like', date('Y-m-d', strtotime($date)) . "%")
            ->limit(1)
            ->delete();
    }
}
