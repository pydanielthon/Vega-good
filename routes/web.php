<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkersController;
use App\Http\Controllers\ContrahentsController;
use App\Http\Controllers\HoursController;
use App\Http\Controllers\BillingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogsController;


// Route::resource('hours', HoursController::class);
// Route::resource('billings', BillingsController::class);
Route::put('users/createViaUsers', [UserController::class, 'createViaUsers'])->name('users.createViaUsers');


use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Auth;

Route::post('billings/generate-pdf/', [PDFController::class, 'generateBillingsPdf'])->name('billings.generate-pdf');

Route::post('billings/generate-pdf-summary/', [PDFController::class, 'generateBillingsPdfSummary'])->name('billings.generate-pdf-summary');
// Route::get('hours/workers-pdf-hours-all', [PDFController::class, 'GetHoursWorkPdf']);

Route::get('hours/contrahents-generate/{id}/{from}/{to}', [PDFController::class, 'GetHoursContrPdf']);
Route::get('hours/workers-generate/{id}/{from}/{to}', [PDFController::class, 'GetHoursWorkPdf'])->name('workers.generate-pdf-work');
Route::get('summary/', [BillingsController::class, 'summaryView'])->name('billings.summary');
Route::post('ajax-billings-summary/', [BillingsController::class, 'summary']);

Route::get('workers/generate-pdf/{id}', [PDFController::class, 'generateWorkerPaidHoursPdf']);
Route::get('workers/generate-pdf-single/{id}', [PDFController::class, 'generatePdf']);
Route::post('workers/{id}', [WorkersController::class, 'getPaid'])->name('workers.getPaid');
Route::post('contrahents/{id}', [ContrahentsController::class, 'getPaidContr'])->name('contrahents.getPaidContr');

Route::get('contrahents/generate-pdf/{id}', [PDFController::class, 'generateContrahentPaidHoursPdf']);
Route::post('contrahents/generate-pdf-data/{id}', [PDFController::class, 'generateContrahentPaidHoursDataPdf'])->name('contrahents.generate-pdf-data');

Route::get('contrahent/status/activate/{id}', [ContrahentsController::class, 'activateWorker']);
Route::get('contrahent/status/deactivate/{id}', [ContrahentsController::class, 'deactivateWorker']);
Route::get('workers/status/activate/{id}', [WorkersController::class, 'activateWorker']);
Route::get('workers/status/deactivate/{id}', [WorkersController::class, 'deactivateWorker']);

Route::get('ajax-request-get/{id}', [WorkersController::class, 'ajaxGet']);
Route::get('ajax-request-get-paid/{id}', [WorkersController::class, 'ajaxGetPaidHours']);

Route::get('ajax-request-get-hours-contrahents/{id}', [ContrahentsController::class, 'ajaxGetHoursFromContrahents']);
Route::get('ajax-request-get-paids-contrahents/{id}', [ContrahentsController::class, 'ajaxGetPaidsFromContrahents']);

Route::get('ajax-destroy-billing/{id}', [BillingsController::class, 'ajaxGetDestroyBilling'])->name('billings.destroyBilling');
Route::post('ajax-billings-get', [BillingsController::class, 'ajaxGetBillings']);

Route::get('all-hours', [HoursController::class, 'list'])->name('hours.list');
Route::post('ajax-hours-all-get', [HoursController::class, 'ajaxGetHours']);
Route::post('ajax-hours-all-get-contrahents', [HoursController::class, 'ajaxGetHoursContr']);

Route::get('check/{id}', [ContrahentsController::class, 'checkForActivity']);
Route::get('hours/{id}/delete', [HoursController::class, 'destroy']);

//Przekierowanie do logowania
Route::group(['middleware' => ['auth']], function() {
    Route::resource('hours', HoursController::class);
    Route::resource('billings', BillingsController::class);
    Route::resource('workers', WorkersController::class);
    Route::resource('contrahents', ContrahentsController::class);
    Route::resource('logs', LogsController::class);
    Route::resource('users', UserController::class);
});


Route::get('/', function () {
    // dd(Auth::user()->id);
    if( Auth::user()){
    return view('home');
    }else{
        return view('auth.login');
    }
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/register', function(){
    return redirect()->route('home');
});

