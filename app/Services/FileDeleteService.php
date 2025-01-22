<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class FileDeleteService
{
    /**
     * Delete an image from the server.
     *
     * @param string $imageUrl
     * @return void
     */
    public function deleteFile($fileUrl, $folderName)
    {
        // Parse the URL
        $path = parse_url($fileUrl, PHP_URL_PATH);
        // Split the path by '/'
        $parts = explode('/', $path);
        // Extract the year from the parts
        $year = $parts[3];
        // Extract the filename from the parts
        $filename = end($parts);
        // Delete the file
        File::delete(public_path() . '/uploads/'.$folderName.'/' . $year . '/' . $filename);
    }
}

?>
