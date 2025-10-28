<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PdfController; // mPdf test controller

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Middleware\AccountsMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ManagerMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect('admin');
});

Route::group(['prefix' => 'mpdf-test'], function(){
    Route::get('/test1', [PdfController::class, 'viewPdf']);
    Route::get('/test2', [PdfController::class, 'test2']);
});

// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes(['register' => false, 'reset' => false]);

// Admin Routes -------------------------------------
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function() {
    
    Route::get('/', [DashboardController::class, 'indexAction'])->name('dashboard');   
    Route::get('/update-password', [DashboardController::class, 'update_password_form'])->name('update.password');
    Route::post('/update-password', [DashboardController::class, 'update_password'])->name('store.update.password');
    Route::get('/quotation', [DashboardController::class, 'quotation'])->name('quotation');

    Route::middleware([AdminMiddleware::class])->group(function () {        
        Route::resource('user', UserController::class);
    });
    
    Route::middleware([AccountsMiddleware::class])->group(function () {   

    });    
});


