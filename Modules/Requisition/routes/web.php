<?php

use Illuminate\Support\Facades\Route;
use Modules\Requisition\Http\Controllers\RequisitionController;
use Modules\Requisition\Http\Controllers\BankController;
use Modules\Requisition\Http\Controllers\CompanyController;
use Modules\Requisition\Http\Controllers\PurposeController;

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
     
     Route::get('requisition/index', [RequisitionController::class, 'index'])->name('requisition.index');

     Route::get('requisition/create', [RequisitionController::class, 'create'])->name('requisition.create');
     Route::post('requisition/store', [RequisitionController::class, 'store'])->name('requisition.store');

     Route::get('requisition/{id}/show', [RequisitionController::class, 'show'])->name('requisition.show');

     Route::get('requisition/{id}/edit', [RequisitionController::class, 'edit'])->name('requisition.edit');
     Route::put('requisition/{id}/edit', [RequisitionController::class, 'update'])->name('requisition.update');
     Route::delete('requisition/file/destroy/{id}', [RequisitionController::class, 'fileDestroy'])->name('requisition.file.destroy');

     Route::get('requisition/{id}/approval', [RequisitionController::class, 'approval'])->name('requisition.approval');
     Route::post('requisition/{id}/store_approval', [RequisitionController::class, 'storeAapproval'])->name('requisition.store_approval');
});
