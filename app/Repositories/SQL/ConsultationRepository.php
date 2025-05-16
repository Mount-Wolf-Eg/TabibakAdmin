<?php

namespace App\Repositories\SQL;

use App\Constants\ConsultationPaymentTypeConstants;
use App\Constants\PaymentMethodConstants;
use App\Constants\PaymentStatusConstants;
use App\Jobs\SendConsultationCall;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;
use App\Repositories\Contracts\CouponContract;
use App\Repositories\Contracts\DoctorContract;
use App\Repositories\Contracts\FileContract;
use App\Repositories\Contracts\NotificationContract;
use App\Services\Repositories\ConsultationNotificationService;
use App\Services\Repositories\PaymentCalculator;
use Carbon\Carbon;

class ConsultationRepository extends BaseRepository implements ConsultationContract
{
    private ConsultationNotificationService $notificationService;

    /**
     * ConsultationRepository constructor.
     * @param Consultation $model
     * @param ConsultationNotificationService $notificationService
     */
    public function __construct(Consultation $model, ConsultationNotificationService $notificationService)
    {
        parent::__construct($model);
        $this->notificationService = $notificationService;
    }

    public function syncRelations($model, $relations): void
    {
        if (!empty($relations['attachments'])){
            foreach ($relations['attachments'] as $attachment){
                $fileModel = resolve(FileContract::class)->find($attachment);
                $model->attachments()->save($fileModel);
            }
        }
        if (!empty($relations['vendors']))
            $model->vendors()->sync($relations['vendors']);

        // this is temporary, till payment gateway is implemented
        if ($model->amount && !$model->payment) {
            $userId     = $model->patient->user_id;
            $doctorId   = $model->doctor?->user_id;
            $baseAmount = $model->amount;

            // Default values before coupon
            $paymentData = [
                'payer_id'       => $userId,
                'beneficiary_id' => $doctorId,
                'amount'         => $baseAmount,
                'transaction_id' => rand(1000000000, 9999999999),
                'currency_id'    => 1,
                'payment_method' => PaymentMethodConstants::CREDIT_CARD->value,
            ];

            $finalAmount = $baseAmount;

            if (!empty($relations['coupon_code'])) {
                $coupon = resolve(CouponContract::class)->findBy('code', $relations['coupon_code'], false);

                if ($coupon?->isValidForUser($userId, $model->medical_speciality_id)) {
                    $finalAmount                    = $coupon->applyDiscount($baseAmount);
                    $paymentData['coupon_id']       = $coupon->id;
                    $paymentData['coupon_discount'] = $baseAmount - $finalAmount;
                }
            }
            
            $calculated = app(PaymentCalculator::class)->calc($finalAmount);

            // Append calculated values to both paymentData and model
            $paymentData = array_merge($paymentData, $calculated);

            // $doctor_amount = $baseAmount - ($paymentData['coupon_discount'] ?? 0);

            $model->update([
                'coupon_id'       => $paymentData['coupon_id'] ?? null,
                'coupon_discount' => $paymentData['coupon_discount'] ?? 0,
                'doctor_amount'   => $calculated['doctor_amount'],
                'app_amount'      => $calculated['app_amount'],
                'tax_amount'      => $calculated['tax_amount'],
                'total_amount'    => $calculated['total_amount'],
            ]);

            // If paying by wallet
            if ((int) request()->payment_type === ConsultationPaymentTypeConstants::WALLET->value) {
                $paymentData['status'] = PaymentStatusConstants::COMPLETED->value;

                // Deduct from patient's wallet and add to doctor's wallet
                $model->patient?->user()->decrement('wallet', $calculated['total_amount']);
                $model->doctor?->user()->increment('wallet', $calculated['doctor_amount']);

                $model->update(['is_active' => true]);
            }

            // Finally, create the payment record
            $model->payment()->create($paymentData);
        }

        // if ($model->status && $model->isCancelled() && $model->payment){
        //     $model->payment->update([
        //         'status' => PaymentStatusConstants::REFUNDED->value
        //     ]);
        // }
    }

    public function afterCreate($model, $attributes): void
    {
        if ($model->medical_speciality_id && $model->doctorScheduleDayShift && $model->doctorScheduleDayShift->day) {
            $shiftDate = $model->doctorScheduleDayShift->day->date->format('Y-m-d'); // تاريخ الشفت
            $fromTime = $model->doctorScheduleDayShift->from_time->format('H:i:s'); // وقت الشفت

            // نبني وقت كامل للتنفيذ = التاريخ + الوقت
            $scheduledTime = Carbon::parse($shiftDate . ' ' . $fromTime);

            if ($scheduledTime->isFuture()) {
                dispatch((new SendConsultationCall($model->id))->delay($scheduledTime));
            } else {
                // لو الشفت وقته عدّى خلاص
                dispatch(new SendConsultationCall($model->id));
            }
        }
        // $this->notificationService->newConsultation($model);
    }

    public function refundAmount($model): void
    {
        $model->payment()->create([
            'payer_id'       => $model->doctor?->user_id,
            'beneficiary_id' => $model->patient->user_id,
            'amount'         => $model->total_amount,
            'transaction_id' => rand(1000000000, 9999999999),
            'currency_id'    => 1,
            'payment_method' => PaymentMethodConstants::WALLET->value,
            'status'         => PaymentStatusConstants::REFUNDED->value
        ]);

        $model->patient?->user()->increment('wallet', $model->total_amount);
        $model->doctor?->user()->decrement('wallet', $model->doctor_amount);
    }
}
