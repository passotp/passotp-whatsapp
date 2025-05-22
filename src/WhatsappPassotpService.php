<?php

namespace Momenshalaby\PassotpWhatsapp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappPassotpService
{
    protected $apiUrl;
    protected $instanceId;
    protected $accessToken;

    public function __construct()
    {
        $this->apiUrl = config('passotp.api_url');
        $this->instanceId = config('passotp.instance_id');
        $this->accessToken = config('passotp.access_token');
    }

    public function sendOtp(string $to, string $message): bool
    {
        $payload = [
            'phone' => '2' . $to,
            'type' => 'text',
            'message' => $message,
            'instance_id' => $this->instanceId,
            'access_token' => $this->accessToken,
        ];

        $response = Http::timeout(10)->post($this->apiUrl, $payload);

        $responseData = $response->json();

        if (!isset($responseData['status']) || $responseData['status'] !== 'success') {
            Log::error("Failed to send message to {$to}. Response: " . json_encode($responseData));
            return false;
        }

        Log::info("Successfully sent message {$message} to WhatsApp number: {$to}");
        return true;
    }
}
