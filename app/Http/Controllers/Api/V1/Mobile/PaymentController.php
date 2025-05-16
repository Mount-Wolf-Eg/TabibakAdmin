<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Http\Resources\PaymentResource;
use App\Models\GeneralSettings;
use App\Models\Payment;
use App\Repositories\Contracts\CouponContract;
use App\Repositories\Contracts\PaymentContract;
use App\Services\Repositories\PaymentCalculator;
use Exception;

class PaymentController extends BaseApiController
{
    /**
     * PaymentController constructor.
     * @param PaymentContract $paymentContract
     */
    private PaymentContract $paymentContract;

    /**
     * PaymentController constructor.
     * @param PaymentContract $paymentContract
     */
    public function __construct(PaymentContract $paymentContract)
    {
        parent::__construct($paymentContract, PaymentResource::class);
        $this->relations = ['payer', 'beneficiary', 'currency', 'payable'];
    }

    public function getAppAndTaxAmount()
    {
        $amount              = request()->get('amount');
        $amountAfterDiscount = $amount;
        $coupon              = request()->get('coupon_code');
        $user                = auth()->user();
        $userId              = $user->id;
        $medicalSpecialtyId  = request()->get('medical_specialty_id');

        if (! $amount || $amount <= 0) {
            return $this->respondWithError(__('Amount must be greater than 0'), 422);
        }

        if ($coupon) {
            $coupon = resolve(CouponContract::class)->findBy('code', $coupon, false);

            if ($coupon?->isValidForUser($userId, $medicalSpecialtyId)) {
                $amountAfterDiscount = $coupon->applyDiscount($amount);
            } else {
                return $this->respondWithError(__('messages.invalid_coupon'));
            }
        }

        $calculated   = app(PaymentCalculator::class)->calc($amountAfterDiscount);

        $appAmount    = $calculated['app_amount'];
        $taxAmount    = $calculated['tax_amount'];
        $totalAmount  = $calculated['total_amount'];

        return [
            'amount'        => $amount,
            'coupon_amount' => $amount - $amountAfterDiscount,
            'app_amount'    => $appAmount,
            'tax_amount'    => $taxAmount,
            'total_amount'  => $totalAmount,
        ];
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function doctorIndex()
    {
        $this->defaultScopes = ['beneficiary' => auth()->id()];
        $totalAmount = $this->contract->sumWithFilters($this->defaultScopes, 'amount');
        $appAmount = $totalAmount * GeneralSettings::getSettingValue('app_payment_percentage');
        // $doctorAmount = $totalAmount - $appAmount;
        $doctorAmount = auth()->user()->wallet;
        return parent::index(['total_amount' => $totalAmount, 'app_amount' => $appAmount, 'doctor_amount' => $doctorAmount]);
    }

    /**
     * Display a listing of the resource.
     * @return mixed
     */
    public function patientIndex()
    {
        $user = auth()->user();
        // $this->defaultScopes = ['payer' => $user->id];
        $this->defaultScopes = ['patient' => $user->id];
        return parent::index(['available_balance' => $user->wallet]);
    }

    public function destroy(Payment $payment)
    {
        try {
            $this->contract->remove($payment);
            return $this->respondWithSuccess(__('Deleted Successfully'));
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }
}
