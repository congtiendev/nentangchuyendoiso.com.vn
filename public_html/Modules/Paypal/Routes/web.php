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
use Modules\Paypal\Http\Controllers\PaypalController;

Route::group(['middleware' => 'PlanModuleCheck:Paypal'], function () {
    Route::prefix('paypal')->group(function() {
        Route::post('/setting/store', [PaypalController::class, 'setting'])->name('paypal.setting.store');
    });
});

Route::post('plan-pay-with/paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal');
Route::get('plan-get-paypal-status/{plan_id}',[PaypalController::class,'planGetPaypalStatus'])->name('plan.get.paypal.status');
Route::get('/invoice/paypal/{invoice_id}/{amount}/{type}',[PaypalController::class,'getInvoicePaymentStatus'])->name('invoice.paypal');

Route::post('pay-with-paypal/{slug?}', [PaypalController::class, 'coursePayWithPaypal'])->name('course.pay.with.paypal');
Route::get('{id}/get-payment-status{slug?}', [PaypalController::class,'GetCoursePaymentStatus'])->name('course.paypal');

Route::prefix('hotel/{slug}')->group(function() {
Route::post('pay-with/paypal', [PaypalController::class,'BookingPayWithPaypal'])->name('pay.with.paypal');
Route::get('{amount}/get-payment-status/{couponid}', [PaypalController::class,'GetBookingPaymentStatus'])->name('booking.get.payment.status');
});
Route::post('/invoice-pay-with/paypal',[PaypalController::class,'invoicePayWithPaypal'])->name('invoice.pay.with.paypal');
