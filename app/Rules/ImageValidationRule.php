<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class ImageValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        if (!is_uploaded_file($value) || !getimagesize($value)) {
            return false;
        }

        $image = getimagesize($value);
        $width = $image[0];
        $height = $image[1];

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 2048; // in kilobytes
        $maxWidth = 1920;
        $maxHeight = 1600;

        $extension = pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION);

        return in_array(strtolower($extension), $allowedExtensions)
            && $value->getSize() <= $maxFileSize * 1024
            && $width <= $maxWidth
            && $height <= $maxHeight;
    }

    public function message()
    {
        return 'The :attribute is invalid.';
    }
}
