<?php

namespace App\Broadcasting;

use App\Services\TaqnyatSmsService;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Config;

class SmsChannel extends Channel
{
    /**
     * @throws Exception
     */
    public function send($notifiable, $notification): void
    {
        $message = $notification->toSms($notifiable);
        $provider = Config::get('sms.default');
        if ($provider === 'taqnyat'){
            $taqnyat = new TaqnyatSmsService();
            $taqnyat->send($message, $notifiable->phone);
        }
    }
}
