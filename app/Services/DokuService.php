<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DokuService
{
    protected string $clientId;

    protected string $secretKey;

    protected string $baseUrl;

    protected string $webhookSecret;

    public function __construct()
    {
        $this->baseUrl = config('doku.production') ? 'https://api.doku.com' : 'https://api-sandbox.doku.com';
        $this->clientId = config('doku.client_id');
        $this->secretKey = config('doku.secret_key');
    }

    public function createPayment(array $data)
    {
        // Build the url
        $targetUrl = '/checkout/v1/payment';
        $url = $this->baseUrl.$targetUrl;

        // Request timestamp
        $requestTimestamp = now()->utc()->toIso8601ZuluString();
        $requestId = $this->generateRequestId();

        // Generate signature
        $signature = $this->generateSignature($data, $targetUrl, $requestId, $requestTimestamp);

        // Call the api
        $response = Http::withHeaders([
            'Client-Id' => $this->clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $requestTimestamp,
            'Signature' => $signature,
        ])->post($url, $data);

        // Check if failed
        if ($response->failed()) {
            Log::error('Doku create payment failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'response' => $response->json(),
            ]);
            throw new \Exception('Doku create payment failed: '.$response->body());
        }

        $responseJson = $response->json();

        return [
            'transaction_id' => $requestId,
            'account' => $responseJson['response']['payment']['url'],
            'code' => null,
        ];
    }

    public function generateSignature(array $body, string $targetUrl, string $requestId, string $requestTimestamp): string
    {
        $digest = base64_encode(hash('sha256', json_encode($body), true));

        $key = 'Client-Id:'.$this->clientId."\n".
                'Request-Id:'.$requestId."\n".
                'Request-Timestamp:'.$requestTimestamp."\n".
                'Request-Target:'.$targetUrl."\n".
                'Digest:'.$digest;

        $signature = 'HMACSHA256='.base64_encode(hash_hmac(
            'sha256',
            $key,
            $this->secretKey,
            true,
        ));

        return $signature;
    }

    public function generateRequestId(): string
    {
        return uniqid().time();
    }

    public function validateCallback(?string $callbackToken): bool
    {
        return true;
    }
}
