<?php

use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::get('/login', 'login');
    Route::get('/logout', 'logout')->middleware('guardType');
});

Route::prefix('hamkadeh')->group(function () {
    Route::apiResource('clinic', ClinicController::class);
    Route::get('report/clinic', [ReportController::class, 'clinic']);
    Route::get('report/advisor', [ReportController::class, 'advisor']);
    Route::get('report/appointment', [ReportController::class, 'appointment']);
    Route::get('report/patient', [ReportController::class, 'patient']);
    Route::apiResource('appointment', AppointmentController::class)->only('index');
    Route::apiResource('advisor', AdvisorController::class)->except('store')
        ->middleware('auth:advisor');
    Route::middleware('auth:patient')->group(function () {
        Route::apiResource('patient', PatientController::class)->except('store');
        Route::apiResource('appointment', AppointmentController::class)->except('index');
    });
});
