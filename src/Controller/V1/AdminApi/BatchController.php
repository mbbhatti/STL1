<?php

namespace App\Controller\V1\AdminApi;

use CRUDlex\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BatchController extends AbstractController
{
    /**
     * @Route("/adminapi/v1/batchDelete/{entity}", name="batchDelete", methods="DELETE")
     *
     * @param Request $request
     * @param Service $service
     * @return Response
     */
    public function batchDelete(Request $request, Service $service)
    {
        $entity = $request->get('entity');
        $ids = json_decode($request->get('ids'));
        $crudData   = $service->getData($entity);

        if ($ids !== null) {
            foreach ($ids as $id) {
                $instance = $crudData->get($id);
                if ($instance) {
                    $crudData->delete($instance);
                }
            }
        }

        return new Response('', 200);
    }
}

