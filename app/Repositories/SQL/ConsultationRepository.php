<?php

namespace App\Repositories\SQL;

use App\Constants\PaymentMethodConstants;
use App\Constants\PaymentStatusConstants;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;
use App\Repositories\Contracts\CouponContract;
use App\Repositories\Contracts\FileContract;

class ConsultationRepository extends BaseRepository implements ConsultationContract
{
    /**
     * ConsultationRepository constructor.
     * @param Consultation $model
     */
    public function __construct(Consultation $model)
    {
        parent::__construct($model);
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
        if ($model->amount && !$model->payment){
            $paymentData = [
                'payer_id' => auth()->id(),
                'beneficiary_id' => $model->doctor?->user_id,
                'amount' => $model->amount,
                'transaction_id' => rand(1000000000, 9999999999),
                'currency_id' => 1,
                'payment_method' => PaymentMethodConstants::CREDIT_CARD->value,
            ];
            if (!empty($relations['coupon_id'])){
                $coupon = resolve(CouponContract::class)->find($model->coupon_id);
                if ($coupon->isValidForUser($model->patient->user_id, $model->medical_speciality_id))
                {
                    $paymentData['coupon_id'] = $coupon->id;
                    $paymentData['amount'] = $coupon->applyDiscount($model->amount);
                }
            }
            $model->payment()->create($paymentData);
        }

        if ($model->status && $model->isCancelled() && $model->payment){
            $model->payment->update([
                'status' => PaymentStatusConstants::REFUNDED->value
            ]);
        }
    }
}
