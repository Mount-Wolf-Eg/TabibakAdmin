<?php

use App\Http\Controllers\Api\V1\FilterController;
use App\Http\Controllers\Api\V1\Mobile\ArticleController;
use App\Http\Controllers\Api\V1\Mobile\AuthController;
use App\Http\Controllers\Api\V1\Mobile\ComplaintController;
use App\Http\Controllers\Api\V1\Mobile\ContactController;
use App\Http\Controllers\Api\V1\Mobile\CouponController;
use App\Http\Controllers\Api\V1\Mobile\DoctorConsultationController;
use App\Http\Controllers\Api\V1\Mobile\DoctorProfileController;
use App\Http\Controllers\Api\V1\Mobile\DoctorScheduleDayShiftController;
use App\Http\Controllers\Api\V1\Mobile\FaqController;
use App\Http\Controllers\Api\V1\Mobile\NotificationController;
use App\Http\Controllers\Api\V1\Mobile\PatientConsultationController;
use App\Http\Controllers\Api\V1\Mobile\DoctorController;
use App\Http\Controllers\Api\V1\Mobile\DoctorScheduleDayController;
use App\Http\Controllers\Api\V1\Mobile\FileController;
use App\Http\Controllers\Api\V1\Mobile\MyFatoorahController;
use App\Http\Controllers\Api\V1\Mobile\PatientProfileController;
use App\Http\Controllers\Api\V1\Mobile\PatientRelativeController;
use App\Http\Controllers\Api\V1\Mobile\PaymentController;
use App\Http\Controllers\Api\V1\Mobile\RateController;
use App\Http\Controllers\Api\V1\Mobile\VendorController;
use App\Http\Controllers\Api\V1\Mobile\Wallet\WalletController;
use App\Models\Consultation;
use App\Services\Repositories\ConsultationNotificationService;
use Illuminate\Support\Facades\Route;

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

        Route::apiResource('contact', ContactController::class)->only('store');

        Route::post('articles/{article}/toggle-like', [ArticleController::class, 'toggleLike']);
        Route::apiResource('notifications', NotificationController::class)->only('index');

        Route::group(['prefix' => 'patient', 'middleware' => 'active_patient'], static function () {
            Route::get('consultation-files', [FileController::class, 'consultationFiles']);

            Route::put('update-main-info', [PatientProfileController::class, 'updateMainInfo']);
            Route::put('update-medical-records', [PatientProfileController::class, 'updateMedicalRecords']);
            Route::put('deactivate', [PatientProfileController::class, 'deactivate']);
            Route::apiResource('relatives', PatientRelativeController::class);
            Route::get('consultations/replies', [PatientConsultationController::class, 'replies']);
            Route::apiResource('consultations', PatientConsultationController::class);
            Route::controller(PatientConsultationController::class)->prefix('consultations')->group(static function () {
                Route::put('/{consultation}/cancel',  'cancel');
                Route::put('/{consultation}/confirm-referral',  'confirmReferral');
                Route::post('/{consultation}/approve-urgent-doctor-offer', 'approveUrgentDoctorOffer');
                Route::post('/{consultation}/reject-urgent-doctor-offer',  'rejectUrgentDoctorOffer');
            });

            Route::get('/referrals/vendors',  [PatientConsultationController::class, 'referralVendors']);
            Route::get('/referrals/other',  [PatientConsultationController::class, 'otherReferralVendors']);
            Route::get('/referrals/test',  [PatientConsultationController::class, 'testReferralVendors']);
            Route::get('/referrals/rays',  [PatientConsultationController::class, 'raysReferralVendors']);
            Route::get('/referrals',  [PatientConsultationController::class, 'referralsByType']);
            Route::post('/referrals/{id}',  [PatientConsultationController::class, 'addFilesToReferral']);

            Route::apiResource('rates', RateController::class)->only('store', 'update', 'destroy');
            Route::apiResource('complaints', ComplaintController::class)->only('store', 'show', 'update', 'destroy');
            Route::apiResource('doctor-schedule-days', DoctorScheduleDayController::class)->only('index');
            Route::get('payments', [PaymentController::class, 'patientIndex']);
            Route::resource('coupons', CouponController::class)->only('index');
            Route::get('coupons/{coupon:code}/apply', [CouponController::class, 'applyCoupon']);

            Route::post('register-user-as-doctor', [AuthController::class, 'registerUserAsDoctor']);

            Route::controller(MyFatoorahController::class)->prefix('payment')->group(function () {
                Route::post('/', 'getUrl');
                Route::get('/callback', 'callback')->name('payment.callback')->withoutMiddleware(['auth:sanctum', 'active_patient']);
            });

            Route::controller(WalletController::class)->prefix('wallet')->group(function () {
                Route::get('/', 'wallet');
                Route::get('/transactions', 'transactions');
                Route::post('/deposit', 'deposit');
                Route::post('/transfer', 'transfer');
            });
        });

        Route::group(['prefix' => 'doctor', 'middleware' => 'active_doctor'], static function () {
            Route::get('consultation-files', [FileController::class, 'consultationFiles']);
            
            Route::put('toggle-urgent-consultation-enabled', [DoctorProfileController::class, 'toggleUrgentConsultationEnabled']);
            Route::put('update-main-info', [DoctorProfileController::class, 'updateMainInfo']);
            Route::put('update-professional-status', [DoctorProfileController::class, 'updateProfessionalStatus']);
            Route::put('update-schedule', [DoctorProfileController::class, 'updateSchedule']);
            Route::post('universities', [DoctorProfileController::class, 'addUniversity']);
            Route::put('universities/{university}', [DoctorProfileController::class, 'updateUniversity']);
            Route::delete('universities/{university}', [DoctorProfileController::class, 'deleteUniversity']);
            Route::put('deactivate', [DoctorProfileController::class, 'deactivate']);
            Route::apiResource('articles', ArticleController::class)->only('store', 'update', 'destroy');
            Route::put('articles/{article}/change-activation', [ArticleController::class, 'changeActivation'])->name('articles.active');
            Route::apiResource('vendors', VendorController::class)->only('index');
            Route::get('/consultations/statistics', [DoctorConsultationController::class, 'statistics']);
            Route::apiResource('consultations', DoctorConsultationController::class)->only('index', 'show');
            Route::controller(DoctorConsultationController::class)->prefix('consultations')->group(static function () {
                Route::post('/{consultation}/vendor-referral','vendorReferral');
                Route::post('/{consultation}/doctor-referral','doctorReferral');
                Route::post('/{consultation}/prescription', 'prescription');
                Route::post('/{consultation}/approve-medical-report', 'approveMedicalReport');
                Route::post('/{consultation}/accept-urgent-case', 'acceptUrgentCase');
                Route::post('/{consultation}/cancel', 'cancel');
                Route::post('/{consultation}/reschedule', 'reschedule');
            });
            Route::get('payments', [PaymentController::class, 'doctorIndex']);
            Route::resource('payments', PaymentController::class)->only('destroy');
            Route::apiResource('doctor-schedule-days', DoctorScheduleDayController::class)->only('store', 'update', 'destroy');
            Route::apiResource('doctor-schedule-day-shifts', DoctorScheduleDayShiftController::class)->only( 'store', 'update', 'destroy');
            Route::get('nearest-doctor-schedule-day/{doctor}', [DoctorScheduleDayController::class, 'nearestAvailableDay']);
        });
    });

    Route::get('consultations/{consultation}/prescription', [PatientConsultationController::class, 'exportPrescription'])->name('consultations.prescription');
    Route::get('consultations/{consultation}/medical-report', [PatientConsultationController::class, 'exportMedicalReport'])->name('consultations.medical_report');
    Route::get('consultations/{consultation}/report', [PatientConsultationController::class, 'exportReport'])->name('consultations.report');

    Route::get('send-test-notification/{consultation}', [PatientConsultationController::class, 'sendTestFcm']);
});