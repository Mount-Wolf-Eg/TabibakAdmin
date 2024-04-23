<?php

namespace App\Services;

use Exception;
use TaqnyatSms;

class TaqnyatSmsService
{
    private string $bearer;

    /**
     * TaqnyatSmsService constructor.
     */
    public function __construct()
    {
        $this->bearer = config('sms.providers.taqnyat.bearer');
    }

    /**
     * Send SMS
     *
     * @param string $message
     * @param string $to
     * @param string|null $senderName
     * @param string|null $smsId
     * @throws Exception
     */
    public function send(string $message, string $to, string $senderName = null, string $smsId = null): void
    {
        try {
            $taqnyat = new TaqnyatSms($this->bearer);
            $taqnyat->sendMsg($message, $to, $senderName, $smsId);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
