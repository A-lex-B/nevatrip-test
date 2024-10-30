<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    /**
     * Создание нового заказа.
     */
    public function store(OrderStoreRequest $request): Order
    {
        return (new OrderService($request->validated()))->store();
    }
}
