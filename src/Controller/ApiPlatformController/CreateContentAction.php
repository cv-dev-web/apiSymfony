<?php

namespace App\Controller\ApiPlatformController;

use App\Entity\Content;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class CreateContentAction extends AbstractController
{
    public function __invoke(Request $request ,FileUploader $fileUploader): Content
    {
        $uploadedFile = $request->files->get('chemin');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new Content();
        $mediaObject->chemin  = $fileUploader->upload($uploadedFile);

        return $mediaObject;
    }
}