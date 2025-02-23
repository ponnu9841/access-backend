<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class ValidationService
{
    /**
     * Delete an image from the server.
     *
     * @param string $imageUrl
     * @return void
     */
    public function galleryValidation($request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'alt' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if (!$validator->passes()) {
            return response([
                'message' => "Invalid Request",
                'errors' => $validator->errors()
            ], 404);
        }
    }
}
