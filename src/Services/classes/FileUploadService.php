<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService implements FileUploadServiceInterface
{
    /**
     * Handles file uploads and returns the new file name.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $uploadDir The directory where the file will be saved.
     *
     * @return string The new file name.
     *
     * @throws FileException if the file cannot be moved.
     */
    public function upload(UploadedFile $file, string $uploadDir): string
    {
        $newFileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($uploadDir, $newFileName);
        } catch (FileException $e) {
            throw new FileException('File upload failed: ' . $e->getMessage());
        }

        return $newFileName;
    }

    public function imageUpload()
    {
        dd('Upload Image');
    }
}
