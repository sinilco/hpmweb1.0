<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\PurchaseOrderController;

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

// home
Route::get('/', function () {
    return view('welcome');
});

// auth routes
Auth::routes();

// dashboard
Route::group(['middleware' => 'auth'], function()
{
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

// users
Route::group(['prefix' => 'users', 'middleware' => ['auth']], function ()
{
    // index users
    Route::get('list', [UserController::class, 'index'])->name('user-list');

    // change user status
    Route::get('status/{userId}/{status}', [UserController::class, 'changeStatus'])->name('user-edit-status');
});

// purchase order
Route::group(['prefix' => 'purchase-order', 'middleware' => ['auth']], function ()
{
    // index PO
    Route::get('list', [PurchaseOrderController::class, 'index'])->name('purchase-order-list');

    // create PO
    Route::get('create', [PurchaseOrderController::class, 'create'])->name('purchase-order-create');

    // post PO
    Route::post('store', [PurchaseOrderController::class, 'store'])->name('purchase-order-store');

    // ajax load product section
    Route::get('ajax-product-section-data', [PurchaseOrderController::class, 'getProductSectionAjax'])->name('get-product-section-ajax');

    // export excel PO
    Route::get('download-excel/', [PurchaseOrderController::class, 'exportExcel'])->name('purchase-order-list-export-excel');

    // export excel detail PO
    Route::get('detail/download-excel/{id}', [PurchaseOrderController::class, 'detailExportExcel'])->name('purchase-order-export-excel');

    // export pdf detail PO
    Route::get('detail/download-pdf/{id}', [PurchaseOrderController::class, 'detailExportPdf'])->name('purchase-order-export-pdf');
});
