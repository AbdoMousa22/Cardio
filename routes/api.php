<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DiagnosesController;
use App\Http\Controllers\Api\DiagnosesMedicineController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::controller(AdminController::class)->group(function(){

  Route::post('admin/register','register');
  Route::post('admin/login','login');
  Route::post('admin/logout','logout')->middleware('auth:admin');
  Route::post('admin/delete','delete')->middleware('auth:admin');
  Route::post('admin/update','update')->middleware('auth:admin');
  Route::get ('admin/view','view')    ->middleware('auth:admin');

});
Route::controller(AuthController::class)->group(function(){

  Route::post('/doctor/register','register');
  Route::post('/doctor/login','login');
  Route::post('/doctor/logout','logout')->middleware('auth:doctor');
  Route::post('/doctor/delete','delete')->middleware('auth:sanctum');
  Route::post('/doctor/update','update')->middleware('auth:doctor');
  Route::get ('/doctor/view','view')    ->middleware('auth:admin');

});

Route::controller(DiagnosesController::class)->group(function(){

    Route::post('diagnoses/insert','insert')                ->middleware('auth:admin');
    Route::post('diagnoses/update/{diagnoses_id}','update') ->middleware('auth:admin');
    Route::get('diagnoses/delete/{diagnoses_id}','delete')  ->middleware('auth:admin');
    Route::get('diagnoses/view','view')                     ->middleware('auth:admin');
    Route::post('diagnoses/search','search')                ->middleware('auth:doctor');

});

Route::controller(MedicineController::class)->middleware('auth:admin')->group(function(){

    Route::post('medicine/insert','insert');
    Route::post('medicine/update/{medicine_id}','update');
    Route::get('medicine/delete/{medicine_id}','delete');
    Route::get('medicine/view','view');

});
Route::controller(DiagnosesMedicineController::class)->middleware('auth:admin')->group(function(){

    Route::post('diagnoses/medicine/insert','insert');
    Route::get('diagnoses/medicine/view','view');
    Route::get('diagnoses/medicine/delete/{diagnosis_id}/{medicine_id}','delete');

});

Route::controller(PatientController::class)->middleware('auth:doctor')->group(function(){

    Route::post('patient/insert','insert');
    Route::post('patient/update/{patient_id}','update');
    Route::get('patient/delete/{patient_id}','delete');
    Route::get('patient/view','view');
    Route::get('patient/day','day');
    Route::get('patient/week','week');
    Route::get('patient/month','month');

});

Route::controller(MessageController::class)->group(function(){

    Route::post('message/insert','insert')->middleware('auth:doctor');
    Route::get('message/delete/{id}','delete')->middleware('auth:admin');
    Route::get('message/view','view')->middleware('auth:admin');

});

