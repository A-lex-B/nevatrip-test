<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|integer|gt:0',
            'event_date' => 'required|date',
            'ticket_adult_price' => 'required|integer|gt:0',
            'ticket_adult_quantity' => 'required|integer',
            'ticket_kid_price' => 'required|integer|gt:0',
            'ticket_kid_quantity' => 'required|integer',
            'ticket_concessionary_price' => 'nullable|required_with:ticket_concessionary_quantity|integer|gt:0',
            'ticket_concessionary_quantity' => 'nullable|integer',
            'ticket_group_price' => 'nullable|required_with:ticket_group_quantity|integer|gt:0',
            'ticket_group_quantity' => 'nullable|integer',
        ];
    }
}
