<?php

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
use Illuminate\Support\Facades\Route;
use Modules\Pos\Http\Controllers\PurchaseController;
use Modules\Pos\Http\Controllers\WarehouseTransferController;
use Modules\Pos\Http\Controllers\PosController;
use Modules\Pos\Http\Controllers\WarehouseController;
use Modules\Pos\Http\Controllers\PurchaseDebitNoteController;
use Modules\Pos\Http\Controllers\ReportController;


Route::group(['middleware' => 'PlanModuleCheck:Pos'], function ()
{
    // Route::middleware(['auth','verified'])->group(function () {  // if any issue so remove commant and also remove ->middleware(['auth']) from all route

        // Route::prefix('pos')->group(function() {
        //     Route::get('/', 'PosController@index');
        // });

        Route::get('dashboard/pos',[PosController::class, 'dashboard'])->name('pos.dashboard')->middleware(['auth']);

        //warehouse
        Route::resource('warehouse', WarehouseController::class)->middleware(['auth',]);

        //warehouse import
        Route::get('warehouse/import/export', [WarehouseController::class, 'fileImportExport'])->name('warehouse.file.import')->middleware(['auth']);
        Route::post('warehouse/import', [WarehouseController::class, 'fileImport'])->name('warehouse.import')->middleware(['auth']);
        Route::get('warehouse/import/modal', [WarehouseController::class, 'fileImportModal'])->name('warehouse.import.modal')->middleware(['auth']);
        Route::post('warehouse/data/import/', [WarehouseController::class, 'warehouseImportdata'])->name('warehouse.import.data')->middleware(['auth']);

        Route::get('productservice/{id}/detail', [WarehouseController::class, 'warehouseDetail'])->name('productservice.detail');

        Route::post('pos/setting/store', [PosController::class, 'setting'])->name('pos.setting.store')->middleware(['auth']);

        //purchase
        Route::resource('purchase', PurchaseController::class);
        Route::post('purchase/items', [PurchaseController::class, 'items'])->name('purchase.items');
        Route::get('purchase/{id}/payment', [PurchaseController::class, 'payment'])->name('purchase.payment');
        Route::post('purchase/{id}/payment', [PurchaseController::class, 'createPayment'])->name('purchase.payment');
        Route::post('purchase/{id}/payment/{pid}/destroy', [PurchaseController::class, 'paymentDestroy'])->name('purchase.payment.destroy');

        Route::post('purchase/product/destroy', [PurchaseController::class, 'productDestroy'])->name('purchase.product.destroy');
        Route::post('purchase/vender', [PurchaseController::class, 'vender'])->name('purchase.vender');
        Route::post('purchase/product', [PurchaseController::class, 'product'])->name('purchase.product');
        Route::get('purchase/create/{cid}', [PurchaseController::class, 'create'])->name('purchase.create');
        Route::get('purchase/{id}/sent', [PurchaseController::class, 'sent'])->name('purchase.sent');
        Route::get('purchase/{id}/resent', [PurchaseController::class, 'resent'])->name('purchase.resent');
        Route::get('purchase/preview/{template}/{color}', [PurchaseController::class, 'previewPurchase'])->name('purchase.preview')->middleware(['auth']);

        Route::post('/purchase/template/setting', [PurchaseController::class, 'savePurchaseTemplateSettings'])->name('purchase.template.setting');

        Route::get('purchase/{id}/debit-note', [PurchaseDebitNoteController::class, 'create'])->name('purchase.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::post('purchase/{id}/debit-note', [PurchaseDebitNoteController::class, 'store'])->name('purchase.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::get('purchase/{id}/debit-note/edit/{cn_id}', [PurchaseDebitNoteController::class, 'edit'])->name('purchase.edit.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::post('purchase/{id}/debit-note/edit/{cn_id}', [PurchaseDebitNoteController::class, 'update'])->name('purchase.edit.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::delete('purchase/{id}/debit-note/delete/{cn_id}', [PurchaseDebitNoteController::class, 'destroy'])->name('purchase.delete.debit.note')->middleware(
            [
                'auth',
            ]
        );
        Route::post('purchase/{id}/file',[PurchaseController::class, 'fileUpload'])->name('purchases.file.upload')->middleware(['auth']);
        Route::delete("purchase/{id}/destroy", [PurchaseController::class, 'fileUploadDestroy'])->name("purchase.attachment.destroy")->middleware(['auth']);

        Route::get('pos-print-setting', [PurchaseController::class, 'posPrintIndex'])->name('pos.print.setting')->middleware('auth');
        Route::post('/purchase/template/setting', [PurchaseController::class, 'savePurchaseTemplateSettings'])->name('purchase.template.setting');
        Route::resource('pos', PosController::class)->middleware(['auth',]);
        Route::get('pos-grid', [PosController::class, 'grid'])->name('pos.grid');
        Route::get('report/pos', [PosController::class, 'report'])->name('pos.report')->middleware(['auth']);
        Route::get('search-products', [PosController::class, 'searchProducts'])->name('search.products')->middleware(['auth']);
        Route::get('name-search-products', [PosController::class, 'searchProductsByName'])->name('name.search.products')->middleware(['auth']);
        Route::post('warehouse-empty-cart', [PosController::class, 'warehouseemptyCart'])->name('warehouse-empty-cart')->middleware(['auth']);
        Route::get('product-categories', [PosController::class, 'getProductCategories'])->name('product.categories')->middleware(['auth']);
        Route::post('empty-cart', [PosController::class, 'emptyCart'])->middleware(['auth']);
        Route::get('add-to-cart/{id}/{session}/{war_id}', [PosController::class, 'addToCart'])->middleware(['auth']);
        Route::delete('remove-from-cart', [PosController::class, 'removeFromCart'])->middleware(['auth']);
        Route::patch('update-cart', [PosController::class, 'updateCart'])->middleware(['auth']);

        Route::get('pos/data/store', [PosController::class, 'store'])->name('pos.data.store')->middleware(['auth',]);

        // thermal print

        Route::get('printview/pos', [PosController::class, 'printView'])->name('pos.printview')->middleware(['auth',]);

        Route::post('/cartdiscount', [PosController::class, 'cartdiscount'])->name('cartdiscount')->middleware(['auth']);

        Route::get('pos/pdf/{id}', [PosController::class, 'pos'])->name('pos.pdf')->middleware(['auth']);
        Route::post('/pos/template/setting', [PosController::class, 'savePosTemplateSettings'])->name('pos.template.setting');
        Route::get('pos/preview/{template}/{color}', [PosController::class, 'previewPos'])->name('pos.preview')->middleware(['auth']);

        Route::get('purchase-grid', [PurchaseController::class, 'grid'])->name('purchase.grid');


        //warehouse-transfer
        Route::resource('warehouse-transfer', WarehouseTransferController::class)->middleware(['auth']);
        Route::post('warehouse-transfer/getproduct', [WarehouseTransferController::class, 'getproduct'])->name('warehouse-transfer.getproduct')->middleware(['auth']);
        Route::post('warehouse-transfer/getquantity', [WarehouseTransferController::class, 'getquantity'])->name('warehouse-transfer.getquantity')->middleware(['auth']);


        //Reports
        Route::get('reports-warehouse', [ReportController::class, 'warehouseReport'])->name('report.warehouse')->middleware(['auth']);
        Route::get('reports-daily-purchase', [ReportController::class, 'purchaseDailyReport'])->name('report.daily.purchase')->middleware(['auth']);
        Route::get('reports-monthly-purchase', [ReportController::class, 'purchaseMonthlyReport'])->name('report.monthly.purchase')->middleware(['auth']);
        Route::get('reports-daily-pos', [ReportController::class, 'posDailyReport'])->name('report.daily.pos')->middleware(['auth']);
        Route::get('reports-monthly-pos', [ReportController::class, 'posMonthlyReport'])->name('report.monthly.pos')->middleware(['auth']);
        Route::get('reports-pos-vs-purchase', [ReportController::class, 'posVsPurchaseReport'])->name('report.pos.vs.purchase')->middleware(['auth']);

    // });
});

Route::get('/vendor/purchase/{id}/', [PurchaseController::class, 'purchaseLink'])->name('purchase.link.copy');
Route::get('/vend0r/bill/{id}/', [PurchaseController::class, 'invoiceLink'])->name('bill.link.copy')->middleware(['auth']);
Route::get('purchase/pdf/{id}', [PurchaseController::class, 'purchase'])->name('purchase.pdf');