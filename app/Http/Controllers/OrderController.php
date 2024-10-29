<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function store(OrderStoreRequest $request)
    {
        $orderService = new OrderService($request->validated());
        $orderService->store();
    }
}
