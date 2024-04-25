<?php

namespace App\Repositories\SQL;

use App\Constants\ConsultationTypeConstants;
use App\Constants\PaymentMethodConstants;
use App\Constants\PaymentStatusConstants;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationContract;
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
            $model->payment()->create([
                'user_id' => auth()->id(),
                'amount' => $model->amount,
                'transaction_id' => rand(1000000000, 9999999999),
                'currency_id' => 1,
                'payment_method' => PaymentMethodConstants::CREDIT_CARD->value
            ]);
        }

        if ($model->isCancelled() && $model->payment){
            $model->payment->update([
                'status' => PaymentStatusConstants::REFUNDED->value
            ]);
        }
    }
}
