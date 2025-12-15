<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\ProviderPortalClient;
use App\Jobs\SyncOrderStatusJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * GET /api/orders
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by name
        if ($name = data_get($request->input('filter'), 'name')) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        // Sorting
        $sort = $request->input('sort');
        $allowedSorts = ['name', 'created_at'];

        if ($sort) {
            $direction = 'asc';
            $field = $sort;

            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $field = substr($sort, 1);
            }

            if (in_array($field, $allowedSorts, true)) {
                $query->orderBy($field, $direction);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return OrderResource::collection($query->paginate());
    }

    /**
     * POST /api/orders
     */
    public function store(
        StoreOrderRequest $request,
        ProviderPortalClient $providerClient
    ) {
        $order = Order::create([
            'name' => $request->string('name'),
            'type' => $request->string('type'),
            'status' => 'ordered',
        ]);

        // 2) An Provider / Mock übergeben
        $providerOrder = $providerClient->createOrder($order->type);

        // 3) Provider-Daten speichern
        $order->update([
            'provider_order_id' => $providerOrder['id'] ?? null,
            'status' => $providerOrder['status'] ?? 'processing',
        ]);

        // 4) Asynchrone Status-Synchronisation starten
        if ($order->provider_order_id) {
            SyncOrderStatusJob::dispatch($order->id);
        }

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * GET /api/orders/{order}
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * DELETE /api/orders/{order}
     */
    public function destroy(
        Order $order,
        ProviderPortalClient $providerClient
    ) {
        if ($order->status !== 'completed') {
            return response()->json([
                'message' => 'Order can only be deleted when status is completed.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Beim Provider löschen
        if ($order->provider_order_id) {
            $providerClient->deleteOrder($order->provider_order_id);
        }

        $order->delete();

        return response()->noContent();
    }
}
