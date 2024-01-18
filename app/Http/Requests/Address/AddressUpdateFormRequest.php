<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateFormRequest extends FormRequest
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
            'type' => [
                'required',
                'string'
            ],
            'country_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'city_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'street_1' => [
                'nullable',
                'string'
            ],
            'street_2' => [
                'nullable',
                'string'
            ],
        ];
    }
}
