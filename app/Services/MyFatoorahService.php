<?php

namespace App\Services;

use Exception;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;

class MyFatoorahService
{
    /**
     * @var array
     */
    private $mfConfig = [];
    private $callbackURL = null;
    private $errorUrl = null;

    public function __construct()
    {
        $this->mfConfig = [
            'apiKey'      => config('myfatoorah.api_key'),
            'isTest'      => config('myfatoorah.test_mode'),
            'countryCode' => config('myfatoorah.country_iso'),
        ];
    }

    public function pay($amount, $paymentId = 0, $sessionId = null, $orderId = '')
    {
        try {
            $data = [
                'CustomerName' => 'FName LName',
                'InvoiceValue' => $amount,
                'CallBackUrl'  => $this->callbackURL,
                'ErrorUrl'     => $this->errorUrl,
                'Language'     => 'en',
            ];

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($data, $paymentId, $orderId, $sessionId);

            return $payment['invoiceURL'];
        } catch (Exception $ex) {
            $exMessage = __('myfatoorah.' . $ex->getMessage());
            info($exMessage);
            return false;
        }
    }
}
