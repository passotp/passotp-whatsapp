<?php

namespace Momenshalaby\PassotpWhatsapp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

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

        try {
            $response = Http::timeout(10)->post($this->apiUrl, $payload);

            // Throw an exception if the HTTP request failed
            $response->throw();

            $responseData = $response->json();

            if (!isset($responseData['status']) || $responseData['status'] !== 'success') {
                throw new Exception("API returned unsuccessful status");
            }

            Log::info("Successfully sent message {$message} to WhatsApp number: {$to}");
            return true;

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("HTTP Request failed for {$to}: " . $e->getMessage());
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("Connection failed for {$to}: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error("Failed to send message to {$to}: " . $e->getMessage());
        }

        return false;
    }
}
