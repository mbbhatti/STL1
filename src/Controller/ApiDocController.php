<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiDocController extends AbstractController
{
    /**
     * @Route("/api-docs", name="api_docs")
     */
    public function apiDocs()
    {
        $response = $this->render('api_doc/swagger.json.twig');
        $response->headers->add(['Content-Type' => 'application/json']);

        return $response;
    }
}

