<?php

namespace App\Http\Requests\Product;

use App\Rules\ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateFormRequest extends FormRequest
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
            'unit_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'image' => [
                'required',
//                'image',
//                new ImageValidationRule()
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'review_id' => [
                'nullable',
                'integer'
            ],
            'category_id' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
}
