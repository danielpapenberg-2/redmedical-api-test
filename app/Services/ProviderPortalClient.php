<?php

namespace App\Services;

interface ProviderPortalClient
{
    /**
     * Erstellt eine Bestellung beim Provider
     *
     * @param string $type
     * @return array{id: string, type: string, status: string}
     */
    public function createOrder(string $type): array;

    /**
     * Holt eine Bestellung vom Provider
     *
     * @param string $providerOrderId
     * @return array{id: string, type: string, status: string}
     */
    public function getOrder(string $providerOrderId): array;

    /**
     * Löscht eine Bestellung beim Provider
     *
     * @param string $providerOrderId
     */
    public function deleteOrder(string $providerOrderId): void;
}
