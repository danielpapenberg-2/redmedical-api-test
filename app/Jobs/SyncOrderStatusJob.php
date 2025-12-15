<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\ProviderPortalClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(ProviderPortalClient $providerClient): void
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        if ($order->status === 'completed') {
            return;
        }

        if (!$order->provider_order_id) {
            return;
        }

        $remoteOrder = $providerClient->getOrder($order->provider_order_id);
        $remoteStatus = $remoteOrder['status'] ?? null;

        if (!in_array($remoteStatus, ['ordered', 'processing', 'completed'], true)) {
            return;
        }

        $order->status = $remoteStatus;
        $order->save();

        if ($order->status !== 'completed') {
            self::dispatch($order->id)
                ->delay(now()->addMinutes(2));
        }
    }
}
