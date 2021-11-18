<?php

namespace App\Controller\V1\AdminApi;

use App\Repository\FormRepository;
use CRUDlex\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/adminapi/search", name="searchForm", methods="GET")
     *
     * @param Request $request
     * @param FormRepository $form
     * @param Service $service
     * @return Response
     */
    public function searchQuery(Request $request, FormRepository $form, Service $service)
    {
        $entity = 'form';
        $search = $request->get('search');
        $ids = $form->searchForms($search);
        $crudData   = $service->getData($entity);
        $definition = $crudData->getDefinition();

        $entities = array_map(function ($id) use ($crudData) {
            return $crudData->get($id);
        }, $ids);

        return $this->render($service->getTemplate('template', 'list', $entity), [
            'crud' => $service,
            'search' => $search,
            'crudEntity' => $entity,
            'crudData' => $crudData,
            'definition' => $definition,
            'entities' => $entities,
            'pageSize' => 100,
            'maxPage' => 1,
            'page' => 1,
            'total' => count($entities),
            'filter' => [],
            'filterActive' => false,
            'sortField' => 'created_at',
            'sortAscending' => true,
            'layout' => $service->getTemplate('layout', 'list', $entity)
        ]);
    }
}

