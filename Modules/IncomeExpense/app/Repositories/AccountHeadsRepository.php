<?php 
namespace Modules\IncomeExpense\Repositories;

use App\Repositories\BaseRepository;
use Modules\IncomeExpense\Models\AccountHeads;

class AccountHeadsRepository extends BaseRepository
{
    /**
     * BaseRepository constructor.
     * 
     * @param  Model  $model
     */
    public function __construct(AccountHeads $model)
    {
        $this->model = $model;
    } 

    public static function getParentHeads()
    {
        return AccountHeads::where('parent_id', '0')
                        ->orderBy('account_head_name', 'asc')
                        ->pluck('account_head_name', 'id')
                        ->toArray();
    } 

    public static function getAllChild()
    {
        return AccountHeads::where('parent_id', '>', '0')
                        ->orderBy('account_head_name', 'asc')
                        ->get();
    }  

    public static function getChildHeads()
    {
        return AccountHeads::where('parent_id', '>', '0')
                        ->orderBy('account_head_name', 'asc')
                        ->pluck('account_head_name', 'id')
                        ->toArray();
    }     

    public static function getCategoryId($id)
    {
        return AccountHeads::where('id', $id)
                        ->pluck('head_category')
                        ->first()
                        ;
    } 

    public static function storeSubHead(object $request)
    {
        $accountHead = new AccountHeads(); 

        $accountHead->account_head_name = $request->get('account_head_name');
        $accountHead->status            = $request->get('status');
        $accountHead->parent_id         = $request->get('parent_id');
        $accountHead->head_category     = self::getCategoryId($request->get('parent_id'));
        $accountHead->save();
        return $accountHead->id;        
    }

    public static function updateSubHead(int $id, object $request)
    {
        $accountHead = AccountHeads::where('parent_id', '>', '0')->firstOrFail();

        $accountHead->account_head_name = $request->get('account_head_name');
        $accountHead->status            = $request->get('status');
        $accountHead->parent_id         = $request->get('parent_id');
        $accountHead->head_category     = self::getCategoryId($request->get('parent_id'));
        $accountHead->save();
        return $accountHead->id;
    }
}
