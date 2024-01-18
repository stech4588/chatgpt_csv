<?php
namespace App\Traits;

use App\Models\Image\Image;
use Illuminate\Support\Facades\Storage;

trait ImageUpload
{
    private function imageUpload($images, $folderName, $moduleName, $moduleId, $userId)
    {
        if (!is_array($images)) {
            $images = [$images];
        }

        $imageNames = [];

            // Check if records exist for the given product_id and delete them
            Image::where('module_id', $moduleId)->where('module_name', $moduleName)->forceDelete();
        foreach ($images as $image) {
            // Original filename without extension
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            // Original file extension
            $extension = $image->getClientOriginalExtension();

            // Make a proper image name
            $imageName = $originalName . '.' . $extension;

            // Make path of the directory to store image
            $path = 'images/' . $folderName;

            // Use the configured disk (local or s3)
            Storage::disk('public')->putFileAs($path, $image, $imageName);

            // Create a new record in the 'images' table
             Image::create([
                'module_name'    => $moduleName,
                'module_id'    => $moduleId,
                'image'         => $imageName,
                'created_by'    => $userId,
            ]);

            // Add the image name to the array
            $imageNames[] = $imageName;
        }

        return $imageNames;
    }
}
