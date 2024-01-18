<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sub_total' => [
                'required',
                'integer',
                'gt:-1'
            ],
            'total_amount' => [
                'required',
                'integer',
                'gt:-1'
            ],
            'product_id' => [
                'required',
                'min:1'
            ],
            'billing_address_id' => [
                'required',
                'min:1'
            ],
            'shipping_address_id' => [
                'required',
                'min:1'
            ],
        ];
    }
}
