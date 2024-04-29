<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client,  $from;
    public function __construct($sms_account_sid, $sms_account_token, $from)
    {
        $this->from = $from;
        $this->client = new Client(
            $sms_account_sid,
            $sms_account_token
        );
    }
    public function sendSMS($to, $message)
    {
        $this->client->messages->create(
            $to,
            [
                'from' => $this->from,
                'body' => $message,
                'content_type' => 'text/html'
            ]
        );
    }
}
