<?php

use Illuminate\Support\Facades\Route;
use Modules\CMMS\Http\Controllers\CMMSController;
use Modules\CMMS\Http\Controllers\CmmsPosController;
use Modules\CMMS\Http\Controllers\CmmsPosPartController;
use Modules\CMMS\Http\Controllers\ComponentController;
use Modules\CMMS\Http\Controllers\ComponentsFieldController;
use Modules\CMMS\Http\Controllers\ComponentsFieldValuesController;
use Modules\CMMS\Http\Controllers\ComponentsLogTimeController;
use Modules\CMMS\Http\Controllers\FormController;
use Modules\CMMS\Http\Controllers\LocationController;
use Modules\CMMS\Http\Controllers\PartController;
use Modules\CMMS\Http\Controllers\PartsLogTimeController;
use Modules\CMMS\Http\Controllers\PmsController;
use Modules\CMMS\Http\Controllers\PmsInvoiceController;
use Modules\CMMS\Http\Controllers\PmsLogTimeController;
use Modules\CMMS\Http\Controllers\SupplierController;
use Modules\CMMS\Http\Controllers\WorkorderController;
use Modules\CMMS\Http\Controllers\WorkOrderImageController;
use Modules\CMMS\Http\Controllers\WorkrequestController;
use Modules\CMMS\Http\Controllers\WosCommentController;
use Modules\CMMS\Http\Controllers\WosInvoiceController;
use Modules\CMMS\Http\Controllers\WosLogTimeController;



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

Route::get('/workrequest/QRCode/{id}', [WorkrequestController::class, 'QRCode'])->name('work_request.QRCode');
Route::get('/workrequest/{id}/{lang}', [LocationController::class, 'work_request_portal'])->name('work_request.portal');
Route::post('/workrequestsend', [WorkrequestController::class, 'store'])->name('work_request.sand');



Route::group(
    ['middleware' => 'PlanModuleCheck:CMMS'],
    function () {

        Route::prefix('cmms')->group(function () {
            Route::get('/', 'CMMSController@index');
        });


        Route::get('dashboard/cmms', ['as' => 'cmms.dashboard', 'uses' => 'CMMSController@index'])->middleware(['auth']);


        Route::resource('parts', PartController::class)->middleware(['auth']);
        Route::post('/parts/associate/{module}/{id}', [PartController::class, 'associateParts'])->name('parts.associate');
        Route::get('/parts/associate/create/{module}/{id}', [PartController::class, 'associatePartsView'])->name('parts.associate.create')->middleware(['auth']);
        Route::any('/parts/associate_remove/{module}/{id}', [PartController::class, 'removeAssociateParts'])->name('parts.associate_remove')->middleware(['auth']);

        Route::resource('workorder', WorkorderController::class)->middleware(['auth']);
        Route::post('/workorder/{id}/file', [WorkorderController::class, 'fileUpload'])->name('workorder.file.upload')->middleware(['auth']);
        Route::get('/workorder/file/{fid}', [WorkorderController::class, 'fileDownload'])->name('workorder.file.download')->middleware(['auth']);
        Route::delete('/workorder/file/delete/{fid}', [WorkorderController::class, 'fileDelete'])->name('workorder.file.delete')->middleware(['auth']);
        Route::get('/wos/componentedit/{id}', [WorkorderController::class, 'componentsedit'])->name('wos.componentedit')->middleware(['auth']);
        Route::post('/wos/componentupdate', [WorkorderController::class, 'componentsupdate'])->name('wos.componentsupdate')->middleware(['auth']);
        Route::get('/workorder/task/complete', [WorkorderController::class, 'taskcomplete'])->name('workorder.task.complete')->middleware(['auth']);
        Route::post('/workorder/task/reopen/{id}', [WorkorderController::class, 'taskreopen'])->name('workorder.task.reopen')->middleware(['auth']);
        Route::post('/workorder/task/updatecomplete', [WorkorderController::class, 'updatetaskcomplete'])->name('workorder.task.updatecomplete')->middleware(['auth']);
        Route::post('/wos/workstatus', [WorkorderController::class, 'workstatus'])->name('wos.workstatus')->middleware(['auth']);
        Route::get('/workorder/complete/task', [WorkorderController::class, 'completetask'])->name('workorder.complete.task')->middleware(['auth']);
        Route::post('/getcomponent', [WorkorderController::class, 'getcomponent'])->name('getcomponent');
        Route::get('/workorder_import', [WorkorderController::class, 'wosimport'])->name('workorder_import')->middleware(['auth']);
        Route::post('/workorder/importcreate', [WorkorderController::class, 'wosimportCreate'])->name('workorder.importcreate')->middleware(['auth']);
        Route::post('workorder/data/import/', [WorkorderController::class, 'workorderImportdata'])->name('workorder.import.data')->middleware(['auth']);
        Route::get('workorder/import/modal', [WorkorderController::class, 'fileImportModal'])->name('workorder.import.modal')->middleware(['auth']);

        Route::resource('pms', PmsController::class)->middleware(['auth']);
        Route::post('/getparts', [PmsController::class, 'getparts'])->name('getparts');

        Route::resource('location', LocationController::class)->middleware(['auth']);
        Route::get('/change-location/{id}', [LocationController::class, 'changeCurrentLocation'])->name('change-location')->middleware(['auth']);

        Route::resource('cmms_pos', CmmsPosController::class)->middleware(['auth']);
        Route::post('get_parts', [CmmsPosController::class, 'get_parts'])->name('get_parts')->middleware(['auth']);
        Route::post('cmms_pos/product/destroy', [CmmsPosController::class, 'productDestroy'])->name('cmms_pos.product.destroy');
        Route::post('/getsupplier', [CmmsPosController::class, 'getsupplier'])->name('getsupplier');
        Route::post('/getitems', [CmmsPosController::class, 'getitems'])->name('getitems');
        Route::post('cmms-pos/items', [CmmsPosController::class, 'items'])->name('cmmsPos.items');
        Route::post('cmms-pos/product', [CmmsPosController::class, 'product'])->name('cmmsPos.product');

        Route::resource('component', ComponentController::class)->middleware(['auth']);
        Route::get('/component/file/{fid}', [ComponentController::class, 'fileDownload'])->name('component.file.download')->middleware(['auth']);
        Route::delete('/component/file/delete/{fid}', [ComponentController::class, 'fileDelete'])->name('component.file.delete')->middleware(['auth']);
        Route::post('/component/{id}/file', [ComponentController::class, 'fileUpload'])->name('component.file.upload')->middleware(['auth']);
        Route::get('/component/associate/create/{module}/{id}', [ComponentController::class, 'associatecomponentView'])->name('component.associate.create');
        Route::any('/component/associate_remove/{module}/{id}', [ComponentController::class, 'removeAssociatecomponent'])->name('component.associate_remove');
        Route::post('/component/associate/{module}/{id}', [ComponentController::class, 'associatecomponent'])->name('component.associate');

        Route::resource('supplier', SupplierController::class)->middleware(['auth']);
        Route::get('/supplier/associate/create/{module}/{id}', [SupplierController::class, 'associateSuppliersView'])->name('supplier.associate.create');
        Route::post('/supplier/associate/{module}/{id}', [SupplierController::class, 'associateSuppliers'])->name('supplier.associate');
        Route::delete('/supplier/associate_remove/{module}/{id}', [SupplierController::class, 'removeAssociateSuppliers'])->name('supplier.associate_remove');

        Route::resource('woslogtime', WosLogTimeController::class)->middleware(['auth']);

        Route::resource('wosinvoice', WosInvoiceController::class)->middleware(['auth']);

        Route::resource('woscomment', WosCommentController::class)->middleware(['auth']);

        Route::resource('cmms_pos', CmmsPosController::class)->middleware(['auth']);

        Route::resource('partslogtime', PartsLogTimeController::class)->middleware(['auth']);

        Route::resource('componentslogtime', ComponentsLogTimeController::class)->middleware(['auth']);

        Route::resource('pmsinvoice', PmsInvoiceController::class)->middleware(['auth']);

        Route::resource('pmslogtime', PmsLogTimeController::class)->middleware(['auth']);

        Route::put('/forms/design/{id}', [FormController::class, 'designUpdate'])->name('forms.design.update')->middleware(['auth']);

        Route::post('ckeditor/upload', [FormController::class, 'upload'])->name('ckeditor.upload');

        Route::post('ckeditors/upload', [FormController::class, 'ckupload'])->name('ckeditors.upload')->middleware('auth');
    }
);
