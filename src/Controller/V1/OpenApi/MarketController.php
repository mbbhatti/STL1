<?php

namespace App\Controller\V1\OpenApi;

use App\Service\Configuration;
use CRUDlex\FileHandler;
use CRUDlex\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class MarketController extends AbstractController
{
    /**
     * @Route("/openapi/v1/market/{id}/{filename}", name="marketMedia", methods="GET")
     *
     * @param Request $request
     * @param Service $service
     * @param Configuration $config
     * @return Response|StreamedResponse
     */
    public function getMarketImage(Request $request, Service $service, Configuration $config)
    {
        $entityName = 'market';
        $crud = $service->getData($entityName);
        $entity = $crud->get($request->get('id'));
        if ($entity === null) {
            return new Response(null, 404);
        }
        $fs = new FileHandler($config->getFileSystem(), $entity->getDefinition());

        return $fs->renderFile($entity, $entityName, 'image');
    }
}

