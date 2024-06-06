<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Plivo\RestClient;
use Plivo\Exceptions\PlivoRestException;

class PlivoService
{
    protected $client;
    protected $from;

    public function __construct($plivo_auth_id, $plivo_auth_token, $plivo_phone_number)
    {
        $this->client = new RestClient($plivo_auth_id, $plivo_auth_token);
        $this->from = $plivo_phone_number;
    }

    public function sendSms($to, $message)
    {
        try {
            $response = $this->client->messages->create(
                $this->from,
                $to,
                $message
            );

            // Log success message
            Log::info([
                'status' => 'success',
                'data' => $response->getMessageUuid(), // Message UUID
            ]);
        } catch (PlivoRestException $e) {
            // Log error message if there's a Plivo API error
            Log::error([
                'status' => 'error',
                'message' => 'Plivo error: ' . $e->getMessage(),
            ]);
        }
    }
}
