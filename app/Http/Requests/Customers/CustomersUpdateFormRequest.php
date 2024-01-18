<?php

namespace App\Http\Requests\Customers;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomersUpdateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string'
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique(User::class)->ignore($this->id),
            ],
            'phone_no' => [
                'required',
                'string',
            ],


        ];
    }
}
