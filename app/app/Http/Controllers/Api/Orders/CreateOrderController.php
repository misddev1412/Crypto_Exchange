<?php

namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Services\Orders\CreateOrderService;

class CreateOrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $response = app(CreateOrderService::class)->create();

        return response()->json($response, $response[RESPONSE_STATUS_KEY] ? 201 : 400);
    }
}
