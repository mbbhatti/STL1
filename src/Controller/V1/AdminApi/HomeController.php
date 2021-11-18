<?php

namespace App\Controller\V1\AdminApi;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $params = ['entity' => 'form'];

        return new RedirectResponse($this->generateUrl('crudList', $params));
    }
}

