<?php

use Illuminate\Support\Facades\Route;
use Modules\Requisition\Http\Controllers\RequisitionController;
use Modules\Requisition\Http\Controllers\BankController;
use Modules\Requisition\Http\Controllers\CompanyController;
use Modules\Requisition\Http\Controllers\PurposeController;
use Modules\Requisition\Http\Controllers\PayeeController;
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

Route::group(['middleware' => ['auth']], function () {

     Route::get('requisition/company', [CompanyController::class, 'index'])->name('company.index');
     Route::get('requisition/company/create', [CompanyController::class, 'create'])->name('company.create');
     Route::post('requisition/company/store', [CompanyController::class, 'store'])->name('company.store');
     Route::get('requisition/company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
     Route::put('requisition/company/edit/{id}', [CompanyController::class, 'update'])->name('company.update');
     Route::delete('requisition/company/delete/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
     Route::get('requisition/company/show/{id}', [CompanyController::class, 'show'])->name('company.show');

     Route::get('requisition/purpose', [PurposeController::class, 'index'])->name('purpose.index');
     Route::get('requisition/purpose/create', [PurposeController::class, 'create'])->name('purpose.create');
     Route::post('requisition/purpose/store', [PurposeController::class, 'store'])->name('purpose.store');
     Route::get('requisition/purpose/edit/{id}', [PurposeController::class, 'edit'])->name('purpose.edit');
     Route::put('requisition/purpose/edit/{id}', [PurposeController::class, 'update'])->name('purpose.update');
     Route::delete('requisition/purpose/delete/{id}', [PurposeController::class, 'destroy'])->name('purpose.destroy');

     Route::get('requisition/payee', [PayeeController::class, 'index'])->name('payee.index');
     Route::get('requisition/payee/create', [PayeeController::class, 'create'])->name('payee.create');
     Route::post('requisition/payee/store', [PayeeController::class, 'store'])->name('payee.store');
     Route::get('requisition/payee/edit/{id}', [PayeeController::class, 'edit'])->name('payee.edit');
     Route::put('requisition/payee/edit/{id}', [PayeeController::class, 'update'])->name('payee.update');
     Route::delete('requisition/payee/delete/{id}', [PayeeController::class, 'destroy'])->name('payee.destroy');

     Route::get('requisition/bank', [BankController::class, 'index'])->name('bank.index');
     Route::get('requisition/bank/create', [BankController::class, 'create'])->name('bank.create');
     Route::post('requisition/bank/create', [BankController::class, 'store'])->name('bank.store');
     Route::get('requisition/bank/edit/{id}', [BankController::class, 'edit'])->name('bank.edit');
     Route::put('requisition/bank/edit/{id}', [BankController::class, 'update'])->name('bank.update');
     Route::get('requisition/bank/cheque-list/{id}', [BankController::class, 'chequeList'])->name('bank.cheque-list');
     Route::get('requisition/bank/cheque-create/{id}', [BankController::class, 'createCheque'])->name('bank.create-cheque');
     Route::post('requisition/bank/save-cheques/{id}', [BankController::class, 'saveCheques'])->name('bank.save-cheques');
     Route::get('requisition/bank/cheque-edit/{id}', [BankController::class, 'editCheque'])->name('bank.edit-cheque');
     Route::put('requisition/bank/cheque-edit/{id}', [BankController::class, 'activityChequeStatus'])->name('bank.activity-cheque-status');

     // Route::patch('requisition/bank/active-toggle/{id}', [BankController::class, 'activeToggle'])->name('bank.active-toggle');
     // Route::patch('requisition/bank/used-toggle/{id}', [BankController::class, 'usedToggle'])->name('bank.used-toggle');

     Route::get('requisition', [RequisitionController::class, 'index'])->name('requisition.index');

     Route::get('requisition/create', [RequisitionController::class, 'create'])->name('requisition.create');
     Route::post('requisition/store', [RequisitionController::class, 'store'])->name('requisition.store');

     Route::get('requisition/{id}/show', [RequisitionController::class, 'show'])->name('requisition.show');

     Route::get('requisition/{id}/edit', [RequisitionController::class, 'edit'])->name('requisition.edit');
     Route::put('requisition/{id}/edit', [RequisitionController::class, 'update'])->name('requisition.update');
     Route::delete('requisition/file/destroy/{id}', [RequisitionController::class, 'fileDestroy'])->name('requisition.file.destroy');

     Route::get('requisition/{id}/approval', [RequisitionController::class, 'approval'])->name('requisition.approval');
     Route::post('requisition/{id}/store_approval', [RequisitionController::class, 'storeAapproval'])->name('requisition.store_approval');
     Route::get('requisition/add-payment/{id}', [RequisitionController::class, 'addPayment'])->name('requisition.add-payment');
     Route::put('requisition/save-payment', [RequisitionController::class, 'savePayment'])->name('requisition.save-payment');
     Route::get('requisition/edit-payment/{id}', [RequisitionController::class, 'editPayment'])->name('requisition.edit-payment');
     Route::put('requisition/edit-payment/{id}', [RequisitionController::class, 'updatePayment'])->name('requisition.update-payment');
     Route::post('requisition/get-valid-cheque-list/', [RequisitionController::class, 'getValidChequeList'])->name('requisition.get-valid-cheque-list');
     Route::delete('/requisition/payment/file/destroy/{id}/{file}', [RequisitionController::class, 'requisitionFileDestroy'])
     ->name('requisition.payment.file.delete');
});
