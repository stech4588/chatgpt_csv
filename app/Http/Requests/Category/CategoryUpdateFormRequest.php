<?php

namespace App\Http\Requests\Category;

use App\Rules\ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateFormRequest extends FormRequest
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
        $rules = [
            'name' => [
                'required',
                'string',
            ],
            'type' => [
                'required',
                'string',
            ],
            'parent_category_id' => [
                'nullable',
                'integer',
            ],
        ];

        // Apply the image validation rule only when the 'image' field is present and not empty.
        $rules['image'] = [
            'nullable',
            'image',
            // new ImageValidationRule()  // You can enable this if needed
        ];

        return $rules;
    }
}
