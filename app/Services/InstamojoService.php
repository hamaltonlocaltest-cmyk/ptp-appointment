<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class InstamojoService
{
    protected string $apiKey;
    protected string $authToken;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey    = (string) config('services.instamojo.key');
        $this->authToken = (string) config('services.instamojo.token');
        $this->baseUrl   = config('services.instamojo.mode') === 'live'
            ? 'https://www.instamojo.com/api/1.1/'
            : 'https://test.instamojo.com/api/1.1/';
    }

    /**
     * Create a hosted payment request on Instamojo and return the full
     * payment_request payload (includes ->id and ->longurl for redirect).
     */
    public function createPaymentRequest(array $data): array
    {
        $response = Http::withHeaders($this->headers())
            ->asForm()
            ->post($this->baseUrl . 'payment-requests/', $data);

        $body = $response->json();

        if (!$response->successful() || empty($body['success'])) {
            throw new RuntimeException(
                'Instamojo payment request failed: ' . ($response->body() ?: 'no response body')
            );
        }

        return $body['payment_request'];
    }

    /**
     * Fetch the current state of a payment request from Instamojo — used to
     * verify the actual payment status server-side rather than trusting the
     * redirect query string alone.
     */
    public function getPaymentRequest(string $paymentRequestId): array
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->baseUrl . "payment-requests/{$paymentRequestId}/");

        $body = $response->json();

        if (!$response->successful() || empty($body['success'])) {
            throw new RuntimeException(
                'Instamojo payment request lookup failed: ' . ($response->body() ?: 'no response body')
            );
        }

        return $body['payment_request'];
    }

    /**
     * Verify the MAC signature Instamojo attaches to webhook POST payloads,
     * using the webhook salt configured in the Instamojo dashboard.
     */
    public function verifyWebhookSignature(array $payload, string $mac): bool
    {
        $salt = config('services.instamojo.webhook_salt');

        if (empty($salt)) {
            return false;
        }

        $data = $payload;
        unset($data['mac']);
        ksort($data);

        $message = implode('|', array_map(fn ($v) => (string) $v, $data));
        $expected = hash_hmac('sha1', $message, $salt);

        return hash_equals($expected, $mac);
    }

    protected function headers(): array
    {
        return [
            'X-Api-Key'    => $this->apiKey,
            'X-Auth-Token' => $this->authToken,
        ];
    }
}
