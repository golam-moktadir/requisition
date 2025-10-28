<?php

namespace Modules\IncomeExpense\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\IncomeExpense\Repositories\AccountHeadsRepository;
use Modules\IncomeExpense\Repositories\DailyIncomeExpenseRepository;
use Modules\IncomeExpense\Models\DailyIncomeExpense;

class DailyIncomeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->get('sdate');
        $account_head = (int) $request->get('account_head', '0');
        if(empty($date)){
            $date = date('Y-m-d');
        }

        return view(
                'incomeexpense::daily-income-expense.index', [
                    'title' => 'Daily Transaction',
                    'sdate' => $date,
                    'account_head' => $account_head,
                    'account_heads' => AccountHeadsRepository::getParentHeads(),
                    'account_sub_heads' => AccountHeadsRepository::getAllChild(),
                    'daily_transactions' => DailyIncomeExpenseRepository::getAllByDateAndAccHead(
                                                $date,
                                                $account_head
                                            )
                ]
            );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view(
                'incomeexpense::daily-income-expense.create', [
                    'title' => 'Create Daily Transaction',
                    'account_heads' => AccountHeadsRepository::getParentHeads(),
                    'account_sub_heads' => AccountHeadsRepository::getAllChild(),
                ]
            );        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DailyIncomeExpenseRepository $repository)
    {
        $validatedData = $request->validate([
            'account_head_id'   => 'required',
            // 'amount'            => 'required|integer|digits_between:-999999,999999',
            'amount'            => 'required|integer',
            'remark'            => 'nullable|max:254',
        ]);

        $request->merge([
            'created_by' => auth()->id()
        ]);

        $repository->create($request->toArray());

        return redirect()->route('income_expense.create_daily_transactions')
                        ->with('success', 'Daily transactoion created successfully!')
                        ;  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showInvoice(int $id, Request $request)
    {
        $invoice = DailyIncomeExpense::where('id', $id)->with('user', 'account_head')->firstOrFail();
        
        return view(
                'incomeexpense::daily-income-expense.invoice-a5', [
                    'title' => 'Invoice',
                    'invoice' => $invoice,
                ]
            );        
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('incomeexpense::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('incomeexpense::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, DailyIncomeExpenseRepository $repository)
    {
        if($repository->delete($id)){
            return back()->with('success', 'Transaction deleted successfully!');
        }
        else {
            return back()->with('error', 'Transaction delete failed.');            
        }
    }
}
