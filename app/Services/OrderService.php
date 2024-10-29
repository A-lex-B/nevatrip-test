<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class OrderService
{
    private int $barcode;

    public function __construct(private array $request) {}

    public function store()
    {
        $this->barcode = $this->createBarcode();

        //$totalPrice = $request->ticket_adult_price * $request->ticket_adult_quantity + $request->ticket_kid_price * $request->ticket_kid_quantity;

        $booking = Http::withQueryParameters(Arr::add($this->request, 'barcode', $this->barcode))
            ->retry(5, 100, function(RequestException $exception, PendingRequest $bookingRequest) {

                if ($exception->response->json('error') != 'barcode already exists') {
                    return false;
                }
                $this->barcode = $this->createBarcode();
                $bookingRequest->withQueryParameters(Arr::add($this->request, 'barcode', $this->barcode));

        })->get('http://api.site.com/book');

        /* for ($i = 1; $i <= 5; $i++) {

            $booking = $this->bookingRequest(Arr::add($this->request, 'barcode', $barcode));

            if ($booking->successful()) {
                break;
            } elseif ($booking->json('error') != 'barcode already exists') {
                throw new Exception('Ошибка бронирования заказа: ' . $booking->json('error', 'неизвестная ошибка'));
            }
        } */

        if ($booking->json('message') == 'order successfully booked') {

            $approving = Http::get('https://api.site.com/approve', ['barcode' => $this->barcode]);
        } else {
            throw new Exception('Ошибка бронирования заказа.');
        }
    }

    private function createBarcode(): int
    {
        return mt_rand(1000000000, 9999999999);
    }

    private function bookingRequest(array $query): Response
    {
        return Http::get('http://api.site.com/book', $query);
    }
}