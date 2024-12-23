<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploadServices
{
    public function __construct()
    {
    }

    // public function uploadImage($form,$uploadDir){
    //     $imagePath = $form->get('imagePath')->getData();

    //     if ($imagePath) {
    //         $newFileName = uniqid() . '.' . $imagePath->guessExtension();

    //         try {
    //             $imagePath->move(
    //                 $uploadDir,
    //                 $newFileName
    //             );
    //         } catch (FileException $e) {
    //             return $e->getMessage();
    //         }

    //         return $newFileName;
    //         $entity->setImagePath('./images/uploads/' . $newFileName);
    //             }
    // }
}
