<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    /**
     * Создание нового заказа.
     */
    public function store(OrderStoreRequest $request): OrderResource
    {
        return (new OrderService($request->validated()))->store();
    }
}
