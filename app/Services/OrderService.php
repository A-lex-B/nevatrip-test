<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class OrderService
{
    private int $barcode;

    public function __construct(private array $request) {}

    public function store(): Order
    {
        $this->barcode = $this->generateBarcode();

        Http::fake([
            'http://api.site.com/book*' => Http::sequence()
                ->push(...$this->bookingResponses('book'))
                ->push(...$this->bookingResponses('book'))
                ->push(...$this->bookingResponses('book')),
            'http://api.site.com/approve*' => Http::response(...$this->bookingResponses('approve'))
        ]);

        $booking = Http::withQueryParameters(Arr::add($this->request, 'barcode', $this->barcode))
            ->retry(3, 100, function(Exception $exception, PendingRequest $bookingRequest) {

                if (
                    ! $exception instanceof RequestException ||
                    $exception->response->json('error') != 'barcode already exists'
                ) {
                    return false;
                }
                $this->barcode = $this->generateBarcode();
                $bookingRequest->withQueryParameters(Arr::add($this->request, 'barcode', $this->barcode));
                return true;

            }, throw: false)->get('http://api.site.com/book');


        if ($booking->json('message') == 'order successfully booked') {

            $approving = Http::get('http://api.site.com/approve', ['barcode' => $this->barcode]);

        } else {

            throw new Exception('Ошибка бронирования заказа: ' . $booking->json('error', 'неизвестная ошибка'));
        }


        if ($approving->json('message') == 'order successfully aproved') {

            $totalPrice = $this->request['ticket_adult_price'] * $this->request['ticket_adult_quantity'] + 
                            $this->request['ticket_kid_price'] * $this->request['ticket_kid_quantity'];

            return Order::create(array_merge($this->request, [
                'barcode' => $this->barcode,
                'total_price' => $totalPrice
            ]));

        } else {

            throw new Exception('Ошибка бронирования заказа: ' . $approving->json('error', 'неизвестная ошибка'));
        }
    }

    private function generateBarcode(): int
    {
        return mt_rand(1000000000, 9999999999);
    }

    private function bookingResponses(string $url): array
    {
        $number = mt_rand(1, 15);

        if ($url == 'book') {

            return match (true) {
                $number <= 10 => [['message' => 'order successfully booked'], 200],
                $number > 10 => [['error' => 'barcode already exists'], 400]
            };

        } elseif ($url == 'approve') {

            return match (true) {
                $number <= 11 => [['message' => 'order successfully aproved'], 200],
                $number == 12 => [['error' => 'event cancelled'], 400],
                $number == 13 => [['error' => 'no tickets'], 400],
                $number == 14 => [['error' => 'no seats'], 400],
                $number == 15 => [['error' => 'fan removed'], 400],
            };

        } else {

            return [];
        }
    }
}