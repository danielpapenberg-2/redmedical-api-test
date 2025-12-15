<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::apiResource('orders', OrderController::class)->only([
    'index',    // GET    /api/orders
    'store',    // POST   /api/orders
    'show',     // GET    /api/orders/{order}
    'destroy',  // DELETE /api/orders/{order}
]);
