<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\PendingRequest;
use RuntimeException;

class RedProviderPortalHttpClient implements ProviderPortalClient
{
    private const TOKEN_CACHE_KEY = 'some_token';

    public function createOrder(string $type): array
    {
        $response = $this->client()->post('/api/v1/orders', [
            'type' => $type,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException('Failed to create order at RedProviderPortal');
        }

        return $response->json();
    }

    public function getOrder(string $providerOrderId): array
    {
        $response = $this->client()->get("/api/v1/order/{$providerOrderId}");

        if (!$response->successful()) {
            throw new RuntimeException('Failed to fetch order from RedProviderPortal');
        }

        return $response->json();
    }

    public function deleteOrder(string $providerOrderId): void
    {
        $response = $this->client()->delete("/api/v1/order/{$providerOrderId}");

        if (!$response->successful()) {
            throw new RuntimeException('Failed to delete order at RedProviderPortal');
        }
    }

    private function client(): PendingRequest
    {
        return Http::withToken($this->getAccessToken())
            ->withOptions([
                'base_uri' => config('services.red_portal.base_url'),
                'verify' => config('services.red_portal.ssl_cert'),
            ])
            ->acceptJson();
    }

    private function getAccessToken(): string
    {
        return Cache::remember(
            self::TOKEN_CACHE_KEY,
            now()->addSeconds(55),
            function () {
                $response = Http::withOptions([
                    'base_uri' => config('services.red_portal.base_url'),
                    'verify' => config('services.red_portal.ssl_cert'),
                ])
                    ->acceptJson()
                    ->post('/api/v1/token', [
                        'client_id' => config('services.red_portal.client_id'),
                        'client_secret' => config('services.red_portal.client_secret'),
                    ]);

                if (!$response->successful()) {
                    throw new RuntimeException(
                        'Failed to fetch access token from RedProviderPortal'
                    );
                }

                return $response->json('access_token');
            }
        );
    }
}
