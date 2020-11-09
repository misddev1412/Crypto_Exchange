<?php

namespace App\Services\Core;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    public function upload($file, $filePath, $fileName, $prefix = '', $suffix = '', $disk = 'public', $width = null, $height = null, $fullView = true, $fileExtension = 'png', $quality = 100)
    {
        if (is_null($disk)) {
            $disk = config('filesystems.default');
        }
        $mimeType = $file->getClientMimeType();
        $imageMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];

        if (in_array($mimeType, $imageMimeTypes)) {

            $imageFile = Image::make($file);

            if (!is_null($width) && !is_null($height) && is_int($width) && is_int($height)) {
                if ($fullView) {
                    $imageFile->resize($width, $height, function ($constraint) {

                        $constraint->aspectRatio();

                    });
                    $background = Image::canvas($width, $height);
                    $imageFile = $background->insert($imageFile, 'center');
                } else {
                    $imageFile->fit($width, $height);
                }
            } elseif (!is_null($width) && is_int($width)) {
                $imageFile->resize($width, null, function ($constraint) {

                    $constraint->aspectRatio();

                });
            } elseif (!is_null($height) && is_int($height)) {
                $imageFile->resize(null, $height, function ($constraint) {

                    $constraint->aspectRatio();

                });
            }

            $imageFile->encode($fileExtension, $quality);
            $fileName = $prefix . '_' . $fileName . '_' . $suffix . '.' . $fileExtension;
            $path = $filePath . '/' . $fileName;
            $stored = Storage::disk($disk)->put($path, $imageFile->__toString());
        } else {
            $fileName = $prefix . '_' . $fileName . '_' . $suffix . '.' . $file->getClientOriginalExtension();
            $stored = $file->storeAs($filePath, $fileName, $disk);

        }

        return isset($stored) ? $fileName : false;
    }
}
