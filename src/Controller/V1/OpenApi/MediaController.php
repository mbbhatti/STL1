<?php

namespace App\Controller\V1\OpenApi;

use App\Service\Configuration;
use App\Service\ImageThumbnail;
use CRUDlex\FileHandler;
use CRUDlex\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use League\Flysystem\FileNotFoundException;
use ImagickException;

class MediaController extends AbstractController
{
    /**
     * @Route("/openapi/v1/media/{id}/{filename}", name="formMedia", methods="GET")
     *
     * @param Request $request
     * @param Service $service
     * @param Configuration $config
     * @param ImageThumbnail $image
     * @return Response|StreamedResponse
     * @throws FileNotFoundException
     * @throws ImagickException
     */
    public function getMediaImage(Request $request, Service $service, Configuration $config, ImageThumbnail $image)
    {
        $entityName = 'media';
        $crudLex = $service->getData($entityName);
        $entity = $crudLex->get($request->get('id'));
        if ($entity === null) {
            return new Response(null, 404);
        }

        $fileSystem = $config->getFileSystem();
        $thumbnail_types = ['image/png', 'image/jpeg'];
        $field = 'filename';
        if ($request->get('thumb') === 'true' && in_array($entity->get('type'), $thumbnail_types)) {
            $image->createThumbnailIfNotExists($entity, $service, $fileSystem);
            $field = 'thumbnail';
        }
        $fs = new FileHandler($fileSystem, $entity->getDefinition());

        return $fs->renderFile($entity, $entityName, $field);
    }
}

