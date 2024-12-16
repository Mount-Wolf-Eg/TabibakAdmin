<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;

class ConnectSaudiSmsService
{
    private string $url;
    private string $user;
    private string $pwd;
    private string $senderid;

    /**
     * ConnectSaudiSmsService constructor.
     */
    public function __construct()
    {
        $this->url      = config('sms.providers.connectsaudi.url');
        $this->user     = config('sms.providers.connectsaudi.user');
        $this->pwd      = config('sms.providers.connectsaudi.pwd');
        $this->senderid = config('sms.providers.connectsaudi.senderid');
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
            $client = new Client([
                'base_uri' => $this->url,
            ]);

            $response = $client->get('sendurl.aspx', [
                'query' => [
                    'user'        => $this->user,
                    'pwd'         => $this->pwd,
                    'senderid'    => $this->senderid,
                    'mobileno'    => $to,
                    'msgtext'     => $message,
                    'priority'    => 'High',
                    'CountryCode' => 'ALL',
                ],
            ]);

            // Parse the response
            $status = $response->getStatusCode();
            $body   = $response->getBody()->getContents();
            // info($status);
            // info($body);
        } catch (Exception $e) {
            info($e);
            throw new Exception($e->getMessage());
        }
    }
}
