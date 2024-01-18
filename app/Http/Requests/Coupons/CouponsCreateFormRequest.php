<?php

namespace App\Http\Requests\Coupons;

use Illuminate\Foundation\Http\FormRequest;

class CouponsCreateFormRequest extends FormRequest
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
            'name' => [
                'required',
                'string'
            ],
            'amount' => [
                'numeric'
            ],
            'percentage' => [
                'numeric'
            ],
            'type' => [
                'required',
                'string',
                'in:amount,percentage'
            ],
            'start_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!strtotime($value) || date('Y-m-d', strtotime($value)) !== $value) {
                        $fail("The $attribute format must be Y-m-d.");
                    }
                },
            ],

            'end_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!strtotime($value) || date('Y-m-d', strtotime($value)) !== $value) {
                        $fail("The $attribute format must be Y-m-d.");
                    }
                },
            ],

        ];
    }
}
