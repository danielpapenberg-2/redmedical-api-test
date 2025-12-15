<?php

namespace App\Services;

use Illuminate\Support\Str;

class RedProviderPortalMockClient implements ProviderPortalClient
{
    /**
     * Erstellt eine Bestellung im Mock-Provider
     */
    public function createOrder(string $type): array
    {
        return [
            'id' => (string) Str::uuid(),
            'type' => $type,
            'status' => 'processing',
        ];
    }

    /**
     * Gibt eine Bestellung zurück
     */
    public function getOrder(string $providerOrderId): array
    {
        return [
            'id' => $providerOrderId,
            'type' => 'connector',
            'status' => 'completed',
        ];
    }

    /**
     * Löscht eine Bestellung im Mock-Provider
     */
    public function deleteOrder(string $providerOrderId): void
    {
        // Mock löscht nichts extern
    }
}
