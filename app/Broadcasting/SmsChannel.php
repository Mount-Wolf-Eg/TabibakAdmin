<?php

namespace App\Broadcasting;

use App\Services\ConnectSaudiSmsService;
use App\Services\TaqnyatSmsService;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Config;

class SmsChannel
{
    /**
     * @throws Exception
     */
    public function send($notifiable, $notification): void
    {
        $message = $notification->toSms($notifiable);
        $provider = Config::get('sms.default');
        if ($provider === 'taqnyat') {
            $taqnyat = new TaqnyatSmsService();
            $taqnyat->send($message, $notifiable->phone);
        } elseif ($provider === 'connectsaudi') {
            $connect_saudi = new ConnectSaudiSmsService();
            $connect_saudi->send($message, '966' . $notifiable->phone);
        }
    }
}
