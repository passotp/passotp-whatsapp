<?php

namespace Momenshalaby\PassotpWhatsapp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PassotpService
{
    protected $apiUrl = 'https://fire.passotp.com';
    protected $instanceId;
    protected $accessToken;

    public function __construct()
    {
        $this->instanceId = config('passotp.instance_id');
        $this->accessToken = config('passotp.access_token');
    }

    public function sendOtp(string $phone, string $otp): bool
    {
        $message = "Your OTP is: {$otp}";

        $payload = [
            'phone' => $phone,
            'type' => 'text',
            'message' => $message,
            'instance_id' => $this->instanceId,
            'access_token' => $this->accessToken,
        ];

        $response = Http::post($this->apiUrl, $payload);

        if (!$response->successful()) {
            Log::error("Passotp failed to send OTP: " . $response->body());
            return false;
        }

        Log::info("Passotp OTP sent successfully to {$phone}");
        return true;
    }
}
