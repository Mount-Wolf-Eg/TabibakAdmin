<?php

use App\Http\Controllers\Api\V1\FilterController;
use App\Http\Controllers\Api\V1\Mobile\ArticleController;
use App\Http\Controllers\Api\V1\Mobile\AuthController;
use App\Http\Controllers\Api\V1\Mobile\ComplaintController;
use App\Http\Controllers\Api\V1\Mobile\DoctorConsultationController;
use App\Http\Controllers\Api\V1\Mobile\DoctorProfileController;
use App\Http\Controllers\Api\V1\Mobile\FaqController;
use App\Http\Controllers\Api\V1\Mobile\PatientConsultationController;
use App\Http\Controllers\Api\V1\Mobile\DoctorController;
use App\Http\Controllers\Api\V1\Mobile\DoctorScheduleDayController;
use App\Http\Controllers\Api\V1\Mobile\FileController;
use App\Http\Controllers\Api\V1\Mobile\PatientProfileController;
use App\Http\Controllers\Api\V1\Mobile\PatientRelativeController;
use App\Http\Controllers\Api\V1\Mobile\RateController;
use App\Http\Controllers\Api\V1\Mobile\VendorController;

Route::group(['middleware' => 'locale'], static function () {
    Route::post('register-user-as-patient', [AuthController::class, 'registerUserAsPatient']);
    Route::post('send-verification-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('login', [AuthController::class, 'login']);

    // visitors apis (not authenticated)
    Route::get('filters/{model}', FilterController::class);
    Route::apiResource('articles', ArticleController::class)->only('index', 'show');
    Route::apiResource('faqs', FaqController::class)->only('index');
    Route::apiResource('doctors', DoctorController::class)->only('index', 'show');
    Route::apiResource('files', FileController::class)->only('store', 'destroy');

    Route::group(['middleware' => 'auth:sanctum'], static function () {

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);

        Route::post('articles/{article}/toggle-like', [ArticleController::class, 'toggleLike']);

        Route::group(['prefix' => 'patient'], static function () {
            Route::put('update-main-info', [PatientProfileController::class, 'updateMainInfo']);
            Route::put('update-medical-records', [PatientProfileController::class, 'updateMedicalRecords']);
            Route::apiResource('relatives', PatientRelativeController::class);
            Route::get('consultations/replies', [PatientConsultationController::class, 'replies']);
            Route::apiResource('consultations', PatientConsultationController::class);
            Route::controller(PatientConsultationController::class)->prefix('consultations')->group(static function () {
                Route::put('/{consultation}/cancel',  'cancel');
                Route::post('/{consultation}/approve-urgent-doctor-offer', 'approveUrgentDoctorOffer');
                Route::post('/{consultation}/reject-urgent-doctor-offer',  'rejectUrgentDoctorOffer');
            });
            Route::apiResource('rates', RateController::class)->only('store', 'update', 'destroy');
            Route::apiResource('complaints', ComplaintController::class)->only('store', 'show', 'update', 'destroy');
            Route::apiResource('doctor-schedule-days', DoctorScheduleDayController::class)->only('index');
        });

        Route::post('register-user-as-doctor', [AuthController::class, 'registerUserAsDoctor']);
        Route::group(['prefix' => 'doctor'], static function () {
            Route::put('update-main-info', [DoctorProfileController::class, 'updateMainInfo']);
            Route::put('update-professional-status', [DoctorProfileController::class, 'updateProfessionalStatus']);
            Route::put('update-schedule', [DoctorProfileController::class, 'updateSchedule']);
            Route::apiResource('articles', ArticleController::class)->only('store', 'update', 'destroy');
            Route::put('articles/{article}/change-activation', [ArticleController::class, 'changeActivation'])->name('articles.active');
            Route::apiResource('vendors', VendorController::class)->only('index');
            Route::get('/consultations/statistics', [DoctorConsultationController::class, 'statistics']);
            Route::apiResource('consultations', DoctorConsultationController::class)->only('index', 'show');
            Route::controller(DoctorConsultationController::class)->prefix('consultations')->group(static function () {
                Route::post('/{consultation}/referral','referral');
                Route::post('/{consultation}/prescription', 'prescription');
                Route::post('/{consultation}/approve-medical-report', 'approveMedicalReport');
                Route::post('/{consultation}/accept-urgent-case', 'acceptUrgentCase');
                Route::post('/{consultation}/cancel', 'cancel');
            });
        });

    });
});
