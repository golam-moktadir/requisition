<?php

use Illuminate\Support\Facades\Route;
use Modules\IncomeExpense\Http\Controllers\IncomeExpenseController;
use Modules\IncomeExpense\Http\Controllers\DailyIncomeExpenseController AS DailyTransactions;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix'=>'income-expense'], function () {

    // Route::resource('/', IncomeExpenseController::class)->names('income_expense');
    
    Route::get('/accounts-head',        [IncomeExpenseController::class, 'accountsHead'])->name('income_expense.accounts_head');
    Route::get('/accounts-head/create', [IncomeExpenseController::class, 'createAccountsHead'])->name('income_expense.create_accounts_head');
    Route::post('/accounts-head/store', [IncomeExpenseController::class, 'storeAccountsHead'])->name('income_expense.store_accounts_head');
    Route::get('/accounts-head/{ie_account_head}/edit', [IncomeExpenseController::class, 'editAccountsHead'])->name('income_expense.edit_accounts_head');
    Route::post('/accounts-head/{ie_account_head}/update', [IncomeExpenseController::class, 'updateAccountsHead'])->name('income_expense.update_accounts_head');


    Route::get('/account-sub-head', [IncomeExpenseController::class, 'accountSubHead'])->name('income_expense.account_sub_head');
    Route::any('/account-sub-head-data', [IncomeExpenseController::class, 'accountSubHeadJson'])->name('income_expense.account_sub_head_data');

    Route::get('/accounts-sub-head/create', [IncomeExpenseController::class, 'createAccountsSubHead'])->name('income_expense.create_accounts_sub_head');
    Route::post('/accounts-sub-head/store', [IncomeExpenseController::class, 'storeAccountsSubHead'])->name('income_expense.store_accounts_sub_head');
    Route::get('/accounts-sub-head/{id}/edit', [IncomeExpenseController::class, 'editAccountsSubHead'])->name('income_expense.edit_accounts_sub_head');
    Route::post('/accounts-sub-head/{id}/update', [IncomeExpenseController::class, 'updateAccountsSubHead'])->name('income_expense.update_accounts_sub_head');


    Route::get('/daily-transaction', [DailyTransactions::class, 'index'])->name('income_expense.daily_transactions');
    Route::get('/daily-transaction/create', [DailyTransactions::class, 'create'])->name('income_expense.create_daily_transactions');
    Route::post('/daily-transaction/store', [DailyTransactions::class, 'store'])->name('income_expense.store_daily_transactions');
    Route::get('/daily-transaction/show/{id}', [DailyTransactions::class, 'showInvoice'])->name('income_expense.show_tran_invoice');
    Route::delete('/daily-transaction/{id}/delete', [DailyTransactions::class, 'destroy'])->name('income_expense.delete_daily_transactions'); 

});
