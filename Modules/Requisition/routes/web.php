<?php

use Illuminate\Support\Facades\Route;
use Modules\Requisition\Http\Controllers\RequisitionController;
use Modules\Requisition\Http\Controllers\BankController;
use Modules\Requisition\Http\Controllers\BankAccountController;
use Modules\Requisition\Http\Controllers\ChequeBookController;
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

// Route::group(['middleware' => ['auth']], function () {
Route::middleware(['auth'])->prefix('requisition')->name('requisition.')->group(function () {

     Route::get('company', [CompanyController::class, 'index'])->name('company.index');
     Route::get('company/create', [CompanyController::class, 'create'])->name('company.create');
     Route::post('company/store', [CompanyController::class, 'store'])->name('company.store');
     Route::get('company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
     Route::put('company/edit/{id}', [CompanyController::class, 'update'])->name('company.update');
     Route::delete('company/delete/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
     Route::get('company/show/{id}', [CompanyController::class, 'show'])->name('company.show');

     Route::get('purpose', [PurposeController::class, 'index'])->name('purpose.index');
     Route::get('purpose/create', [PurposeController::class, 'create'])->name('purpose.create');
     Route::post('purpose/store', [PurposeController::class, 'store'])->name('purpose.store');
     Route::get('purpose/edit/{id}', [PurposeController::class, 'edit'])->name('purpose.edit');
     Route::put('purpose/edit/{id}', [PurposeController::class, 'update'])->name('purpose.update');
     Route::delete('purpose/delete/{id}', [PurposeController::class, 'destroy'])->name('purpose.destroy');

     Route::get('payee', [PayeeController::class, 'index'])->name('payee.index');
     Route::get('payee/create', [PayeeController::class, 'create'])->name('payee.create');
     Route::post('payee/store', [PayeeController::class, 'store'])->name('payee.store');
     Route::get('payee/edit/{id}', [PayeeController::class, 'edit'])->name('payee.edit');
     Route::put('payee/edit/{id}', [PayeeController::class, 'update'])->name('payee.update');
     Route::delete('payee/delete/{id}', [PayeeController::class, 'destroy'])->name('payee.destroy');

     Route::get('bank/get-data-list', [BankController::class, 'getDataList'])->name('bank.get-data-list');
     Route::resource('bank', BankController::class)->except(['show']);

     Route::get('bank-account/get-data-list', [BankAccountController::class, 'getDataList'])->name('bank-account.get-data-list');
     Route::resource('bank-account', BankAccountController::class);     

     Route::get('cheque/get-data-list', [ChequeBookController::class, 'getDataList'])->name('cheque.get-data-list');
     Route::resource('cheque', ChequeBookController::class);

     Route::get('/get-data-list', [RequisitionController::class, 'getDataList'])->name('get-data-list');
     Route::get('/', [RequisitionController::class, 'index'])->name('index');

     Route::get('create', [RequisitionController::class, 'create'])->name('create');
     Route::post('store', [RequisitionController::class, 'store'])->name('store');

     Route::get('{id}/show', [RequisitionController::class, 'show'])->name('show');

     Route::get('{id}/edit', [RequisitionController::class, 'edit'])->name('edit');
     Route::put('{id}/edit', [RequisitionController::class, 'update'])->name('update');
     Route::delete('delete/{id}', [RequisitionController::class, 'destroy'])->name('destroy');
     Route::delete('file/destroy/{id}', [RequisitionController::class, 'fileDestroy'])->name('file.destroy');

     Route::get('{id}/approval', [RequisitionController::class, 'approval'])->name('requisition.approval');
     Route::post('{id}/store_approval', [RequisitionController::class, 'storeAapproval'])->name('requisition.store_approval');
     Route::get('add-payment/{id}', [RequisitionController::class, 'addPayment'])->name('requisition.add-payment');
     Route::put('save-payment', [RequisitionController::class, 'savePayment'])->name('requisition.save-payment');
     Route::get('edit-payment/{id}', [RequisitionController::class, 'editPayment'])->name('requisition.edit-payment');
     Route::put('edit-payment/{id}', [RequisitionController::class, 'updatePayment'])->name('requisition.update-payment');
     Route::post('get-valid-cheque-list/', [RequisitionController::class, 'getValidChequeList'])->name('requisition.get-valid-cheque-list');
     Route::delete('payment/file/destroy/{id}/{file}', [RequisitionController::class, 'requisitionFileDestroy'])
          ->name('requisition.payment.file.delete');
});
