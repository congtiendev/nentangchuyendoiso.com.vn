<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BanktransferController;
use App\Http\Controllers\BorrowAssetRecordController;
use App\Http\Controllers\BorrowEmployeeRecordController;
use App\Http\Controllers\ManagerFileControler;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Company\SettingsController as CompanySettingsController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\HelpdeskConversionController;
use App\Http\Controllers\HelpdeskTicketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdmin\SettingsController as SuperAdminSettingsController;
use App\Http\Controllers\TechnicalSupport;
use App\Http\Controllers\WorkSpaceController;
use App\Http\Controllers\CustomNotificationController;
use App\Http\Controllers\HelpdeskTicketCategoryController;
use App\Http\Controllers\SignatureSampleController;
use App\Http\Controllers\SignatureSampleTypeController;
use App\Models\BorrowAssetRecord;

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


// Auth::routes();
require __DIR__ . '/auth.php';
Route::resource('signature-sample', SignatureSampleController::class);
Route::resource('signature-sample-type', SignatureSampleTypeController::class);
Route::DELETE('signature-sample-type/{id}', [SignatureSampleTypeController::class, 'destroy'])->name('signature-sample-type.destroy');
Route::post('signature-sample-type/{id}', [SignatureSampleTypeController::class, 'update'])->name('signature-sample-type.update');
Route::get('signature-sample/{id}', [SignatureSampleController::class, 'show'])->name('signature-sample.show');
Route::DELETE('signature-sample/{id}', [SignatureSampleController::class, 'destroy'])->name('signature-sample.destroy');

Route::get('/register/{lang?}', [RegisteredUserController::class, 'create'])->name('register');
Route::get('/login/{lang?}', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::get('/forgot-password/{lang?}', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::get('/verify-email/{lang?}', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

Route::get('helpdesk/show-video', [TechnicalSupport::class, 'showVideo'])->name('technical.show-video');
Route::get('helpdesk/show-pdf', [TechnicalSupport::class, 'showPdf'])->name('technical.show-pdf');

Route::group(['middleware' => 'auth'], function () {
    Route::controller(CustomNotificationController::class)->group(function () {
        Route::get('notification', [CustomNotificationController::class, 'index'])->name('all.notification');
        Route::post('read-notification', [CustomNotificationController::class, 'readNotification'])->name('read.notification');
    });
});

// module page before login
Route::get('add-on', [HomeController::class, 'Software'])->name('apps.software');
Route::get('add-on/details/{slug}', [HomeController::class, 'SoftwareDetails'])->name('software.details');
Route::get('pricing', [HomeController::class, 'Pricing'])->name('apps.pricing');
Route::get('/', [HomeController::class, 'index'])->name('start');
Route::middleware(['auth', 'verified'])->group(function () {

    //Role & Permission
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    //dashbord
    if (module_is_active('GoogleAuthentication')) {
        Route::get('/dashboard', [HomeController::class, 'Dashboard'])->name('dashboard')->middleware(
            [
                '2fa',
            ]
        );
        Route::get('/home', [HomeController::class, 'Dashboard'])->name('home')->middleware(
            [
                '2fa',
            ]
        );
    } else {
        Route::get('/dashboard', [HomeController::class, 'Dashboard'])->name('dashboard');
        Route::get('/home', [HomeController::class, 'Dashboard'])->name('home');
    }

    // settings
    Route::resource('settings', SettingsController::class);
    Route::post('settings-save', [CompanySettingsController::class, 'store'])->name('settings.save');
    Route::post('company/settings-save', [CompanySettingsController::class, 'store'])->name('company.settings.save');
    Route::post('super-admin/settings-save', [SuperAdminSettingsController::class, 'store'])->name('super.admin.settings.save');
    Route::post('super-admin/system-settings-save', [SuperAdminSettingsController::class, 'SystemStore'])->name('super.admin.system.setting.store');
    Route::post('company/system-settings-save', [CompanySettingsController::class, 'SystemStore'])->name('company.system.setting.store');
    Route::post('company-setting-save', [CompanySettingsController::class, 'companySettingStore'])->name('company.setting.save');

    Route::post('email-settings-save', [SettingsController::class, 'mailStore'])->name('email.setting.store');
    Route::post('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
    Route::post('test-mail-send', [SettingsController::class, 'sendTestMail'])->name('test.mail.send');
    Route::post('email-notification-settings-save', [SettingsController::class, 'mailNotificationStore'])->name('email.notification.setting.store');

    Route::post('cookie-settings-save', [SuperAdminSettingsController::class, 'CookieSetting'])->name('cookie.setting.store');
    Route::post('pusher-setting', [SuperAdminSettingsController::class, 'savePusherSettings'])->name('pusher.setting');
    Route::post('seo/setting/save', [SuperAdminSettingsController::class, 'seoSetting'])->name('seo.setting.save');
    Route::post('storage-settings-save', [SuperAdminSettingsController::class, 'storageStore'])->name('storage.setting.store');
    Route::post('ai/key/setting/save', [SuperAdminSettingsController::class, 'aiKeySettingSave'])->name('ai.key.setting.save');

    Route::get('/setting/section/{module}/{methord?}', [SettingsController::class, 'getSettingSection'])->name('setting.section.get');

    // bank-transfer
    Route::resource('bank-transfer-request', BanktransferController::class);
    Route::post('bank-transfer-setting', [BanktransferController::class, 'setting'])->name('bank.transfer.setting');
    Route::post('/bank/transfer/pay', [BanktransferController::class, 'planPayWithBank'])->name('plan.pay.with.bank');


    Route::get('invoice-bank-request/{id}', [BanktransferController::class, 'invoiceBankRequestEdit'])->name('invoice.bank.request.edit');
    Route::post('bank-transfer-request-edit/{id}', [BanktransferController::class, 'invoiceBankRequestupdate'])->name('invoice.bank.request.update');

    //users
    Route::resource('users', UserController::class);
    Route::get('users/list/view', [UserController::class, 'List'])->name('users.list.view');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('edit-profile', [UserController::class, 'editprofile'])->name('edit.profile');
    Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');
    Route::any('user-reset-password/{id}', [UserController::class, 'UserPassword'])->name('users.reset');
    Route::get('user-login/{id}', [UserController::class, 'LoginManage'])->name('users.login');
    Route::post('user-reset-password/{id}', [UserController::class, 'UserPasswordReset'])->name('user.password.update');
    Route::get('users/{id}/login-with-company', [UserController::class, 'LoginWithCompany'])->name('login.with.company');
    Route::get('company-info/{id}', [UserController::class, 'CompnayInfo'])->name('company.info');
    Route::post('user-unable', [UserController::class, 'UserUnable'])->name('user.unable');

    //User Log
    Route::get('users/logs/history', [UserController::class, 'UserLogHistory'])->name('users.userlog.history');
    Route::get('users/logs/{id}', [UserController::class, 'UserLogView'])->name('users.userlog.view');
    Route::delete('users/logs/destroy/{id}', [UserController::class, 'UserLogDestroy'])->name('users.userlog.destroy');

    // users import
    Route::get('users/import/export', [UserController::class, 'fileImportExport'])->name('users.file.import');
    Route::get('users/import/modal', [UserController::class, 'fileImportModal'])->name('users.import.modal');
    Route::post('users/import', [UserController::class, 'fileImport'])->name('users.import');
    Route::post('users/data/import/', [UserController::class, 'UserImportdata'])->name('users.import.data');


    // impersonating
    Route::get('login-with-company/exit', [UserController::class, 'ExitCompany'])->name('exit.company');

    // Language
    Route::get('/lang/change/{lang}', [LanguageController::class, 'changeLang'])->name('lang.change');
    Route::get('langmanage/{lang?}/{module?}', [LanguageController::class, 'index'])->name('lang.index');
    Route::get('create-language', [LanguageController::class, 'create'])->name('create.language');
    Route::post('langs/{lang?}/{module?}', [LanguageController::class, 'storeData'])->name('lang.store.data');
    Route::post('disable-language', [LanguageController::class, 'disableLang'])->name('disablelanguage');
    Route::any('store-language', [LanguageController::class, 'store'])->name('store.language');
    Route::delete('/lang/{id}', [LanguageController::class, 'destroy'])->name('lang.destroy');
    // End Language

    // Workspace
    Route::resource('workspace', WorkSpaceController::class);
    Route::get('workspace/change/{id}', [WorkSpaceController::class, 'change'])->name('workspace.change');

    // End Workspace

    // Plans
    Route::resource('plans', PlanController::class);

    Route::get('plan/list', [PlanController::class, 'PlanList'])->name('plan.list');
    Route::post('plan/store', [PlanController::class, 'PlanStore'])->name('plan.store');

    Route::get('plan/active', [PlanController::class, 'ActivePlans'])->name('active.plans');
    Route::any('plan/package-data', [PlanController::class, 'PackageData'])->name('package.data');
    Route::get('plan/plan-buy/{id}', [PlanController::class, 'PlanBuy'])->name('plan.buy');
    Route::get('plan/plan-trial/{id}', [PlanController::class, 'PlanTrial'])->name('plan.trial');
    Route::get('plan/order', [PlanController::class, 'orders'])->name('plan.order.index');
    Route::get('add-one/detail/{id}', [PlanController::class, 'AddOneDetail'])->name('add-one.detail');
    Route::post('add-one/detail/save/{id}', [PlanController::class, 'AddOneDetailSave'])->name('add-one.detail.save');

    Route::post('company/settings-save', [CompanySettingsController::class, 'store'])->name('company.settings.save');
    Route::post('super-admin/settings-save', [SuperAdminSettingsController::class, 'store'])->name('super.admin.settings.save');
    Route::post('storage-settings-save', [SuperAdminSettingsController::class, 'storageStore'])->name('storage.setting.store');

    // Coupon
    Route::resource('coupons', CouponController::class);
    Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
    // end Coupon

    // Module Install
    Route::get('modules/list', [ModuleController::class, 'index'])->name('module.index');
    Route::get('modules/add', [ModuleController::class, 'add'])->name('module.add');
    Route::post('install-modules', [ModuleController::class, 'install'])->name('module.install');
    Route::post('remove-modules/{module}', [ModuleController::class, 'remove'])->name('module.remove');
    Route::post('modules-enable', [ModuleController::class, 'enable'])->name('module.enable');
    Route::get('cancel/add-on/{name}', [ModuleController::class, 'CancelAddOn'])->name('cancel.add.on');
    // End Module Install

    // Email Templates
    Route::resource('email-templates', EmailTemplateController::class);
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'show'])->name('manage.email.language');
    Route::put('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language');
    Route::put('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language');
    Route::resource('email_template', EmailTemplateController::class);
    // End Email Templates

    // helpdesk
    Route::get('helpdesk/pdf/{id}', [HelpdeskTicketController::class, 'download_pdf'])->name('helpdesk.pdf');
    Route::get('helpdesk/export', [HelpdeskTicketController::class, 'export'])->name('helpdesk.export');
    Route::resource('helpdesk', HelpdeskTicketController::class);
    Route::resource('helpdeskticket-category', HelpdeskTicketCategoryController::class);
    Route::get('helpdesk-tickets/search/{status?}', [HelpdeskTicketController::class, 'index'])->name('helpdesk-tickets.search');
    Route::post('helpdesk-ticket/getUser', [HelpdeskTicketController::class, 'getUser'])->name('helpdesk-tickets.getuser');
    Route::post('helpdesk-ticket/{id}/conversion', [HelpdeskConversionController::class, 'store'])->name('helpdesk-ticket.conversion.store');
    Route::post('helpdesk-ticket/{id}/note', [HelpdeskTicketController::class, 'storeNote'])->name('helpdesk-ticket.note.store');
    Route::delete('helpdesk-ticket-attachment/{tid}/destroy/{id}', [HelpdeskTicketController::class, 'attachmentDestroy'])->name('helpdesk-ticket.attachment.destroy');
    Route::post('helpdesk-ticket/stamp/{id}', [HelpdeskTicketController::class, 'stamp'])->name('helpdesk-ticket.stamp');
    // End helpdesk


    Route::group(['middleware' => 'PlanModuleCheck:Account-Taskly'], function () {
        // invoice
        Route::post('invoice/customer', [InvoiceController::class, 'customer'])->name('invoice.customer');
        Route::post('invoice-attechment/{id}', [InvoiceController::class, 'invoiceAttechment'])->name('invoice.file.upload');
        Route::delete('invoice-attechment/destroy/{id}', [InvoiceController::class, 'invoiceAttechmentDestroy'])->name('invoice.attachment.destroy');
        Route::post('invoice/product', [InvoiceController::class, 'product'])->name('invoice.product');
        Route::get('invoice/{id}/duplicate', [InvoiceController::class, 'duplicate'])->name('invoice.duplicate');
        Route::get('invoice/items', [InvoiceController::class, 'items'])->name('invoice.items');
        Route::post('invoice/product/destroy', [InvoiceController::class, 'productDestroy'])->name('invoice.product.destroy');
        Route::get('invoice/grid/view', [InvoiceController::class, 'Grid'])->name('invoice.grid.view');
        Route::resource('invoice', InvoiceController::class);
        Route::get('invoice/create/{cid}', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::get('/invoice/pay/{invoice}', [InvoiceController::class, 'payinvoice'])->name('pay.invoice');
        Route::get('invoice/{id}/sent', [InvoiceController::class, 'sent'])->name('invoice.sent');
        Route::get('invoice/{id}/resent', [InvoiceController::class, 'resent'])->name('invoice.resent');
        Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');
        Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf');
        Route::get('invoice/{id}/payment', [InvoiceController::class, 'payment'])->name('invoice.payment');
        Route::post('invoice/{id}/payment', [InvoiceController::class, 'createPayment'])->name('invoice.payment');
        Route::post('invoice/{id}/payment/{pid}/', [InvoiceController::class, 'paymentDestroy'])->name('invoice.payment.destroy');
        Route::get('invoice/{id}/send', [InvoiceController::class, 'customerInvoiceSend'])->name('invoice.send');
        Route::post('invoice/{id}/send/mail', [InvoiceController::class, 'customerInvoiceSendMail'])->name('invoice.send.mail');
        Route::post('invoice/section/type', [InvoiceController::class, 'InvoiceSectionGet'])->name('invoice.section.type');
        Route::get('delivery-form/pdf/{id}', [InvoiceController::class, 'pdf'])->name('delivery-form.pdf');

        // Proposal
        Route::post('proposal-attechment/{id}', [ProposalController::class, 'proposalAttechment'])->name('proposal.file.upload');
        Route::delete('proposal-attechment/destroy/{id}', [ProposalController::class, 'proposalAttechmentDestroy'])->name('proposal.attachment.destroy');
        Route::post('proposal/customer', [ProposalController::class, 'customer'])->name('proposal.customer');
        Route::post('proposal/product', [ProposalController::class, 'product'])->name('proposal.product');
        Route::get('proposal/{id}/convert', [ProposalController::class, 'convert'])->name('proposal.convert');
        Route::get('proposal/{id}/duplicate', [ProposalController::class, 'duplicate'])->name('proposal.duplicate');
        Route::get('proposal/items', [ProposalController::class, 'items'])->name('proposal.items');
        Route::post('proposal/product/destroy', [ProposalController::class, 'productDestroy'])->name('proposal.product.destroy');
        Route::resource('proposal', ProposalController::class);
        Route::get('proposal/grid/view', [ProposalController::class, 'Grid'])->name('proposal.grid.view');
        Route::get('proposal/create/{cid}', [ProposalController::class, 'create'])->name('proposal.create');
        Route::get('proposal/{id}/status/change', [ProposalController::class, 'statusChange'])->name('proposal.status.change');
        Route::get('proposal/{id}/resent', [ProposalController::class, 'resent'])->name('proposal.resent');
        Route::post('proposal/section/type', [ProposalController::class, 'ProposalSectionGet'])->name('proposal.section.type');
        Route::get('proposal/{id}/sent', [ProposalController::class, 'sent'])->name('proposal.sent');
    });
    // invoices template setting save
    Route::post('/invoices/template/setting', [InvoiceController::class, 'saveTemplateSettings'])->name('invoice.template.setting');
    Route::get('/invoices/preview/{template}/{color}', [InvoiceController::class, 'previewInvoice'])->name('invoice.preview');

    // proposal template setting save
    Route::get('/proposal/preview/{template}/{color}', [ProposalController::class, 'previewInvoice'])->name('proposal.preview');
    Route::post('/proposal/template/setting', [ProposalController::class, 'saveTemplateSettings'])->name('proposal.template.setting');
});
Route::get('module/reset', [ModuleController::class, 'ModuleReset'])->name('module.reset');
Route::get('guest/module/selection', [ModuleController::class, 'GuestModuleSelection'])->name('guest.module.selection');

// cookie
Route::get('cookie/consent', [SuperAdminSettingsController::class, 'CookieConsent'])->name('cookie.consent');

// cache
Route::get('/config-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Cache Clear Successfully');
})->name('config.cache');

//helpdesk
Route::post('helpdesk-ticket/{id}', [HelpdeskTicketController::class, 'reply'])->name('helpdesk-ticket.reply');
Route::get('helpdesk-ticket-show/{id}', [HelpdeskTicketController::class, 'show'])->name('helpdesk.view');

// invoice
Route::get('/invoice/pay/{invoice}', [InvoiceController::class, 'payinvoice'])->name('pay.invoice');
Route::get('invoice/pdf/{id}', [InvoiceController::class, 'invoice'])->name('invoice.pdf');
Route::post('/bank/transfer/invoice', [BanktransferController::class, 'invoicePayWithBank'])->name('invoice.pay.with.bank');

// proposal
Route::get('/proposal/pay/{proposal}', [ProposalController::class, 'payproposal'])->name('pay.proposalpay');
Route::get('proposal/pdf/{id}', [ProposalController::class, 'proposal'])->name('proposal.pdf');
// danh sách mượn
Route::get('/manager-file', [ManagerFileControler::class, 'index'])->name('manager-file.index');
Route::get('/manager-file-dc', [ManagerFileControler::class, 'index2'])->name('manager-file.index2');
Route::get('/manager-file/create', [ManagerFileControler::class, 'create'])->name('manager-file.create');
Route::post('/manager-file/store', [ManagerFileControler::class, 'store'])->name('manager-file.store');
Route::get('/manager-file/edit/{id}', [ManagerFileControler::class, 'edit'])->name('manager-file.edit');
Route::post('/manager-file/update/{id}', [ManagerFileControler::class, 'update'])->name('manager-file.update');
Route::delete('/manager-file/destroy/{id}', [ManagerFileControler::class, 'destroy'])->name('manager-file.destroy');

//Hồ sơ duyệt mượn lao động
Route::resource('borrow-employee-records', BorrowEmployeeRecordController::class);
Route::get('/get-users-by-project/{projectId}', [BorrowEmployeeRecordController::class, 'getUsersByProject']);

// hồ sơ duyệt mượn dụng cụ
Route::resource('borrow-asset-records', BorrowAssetRecordController::class);



//activity
Route::get('log-proposal', [ProposalController::class, 'activityLogProposal'])->name('activityLog.proposal')->middleware(['auth']);



