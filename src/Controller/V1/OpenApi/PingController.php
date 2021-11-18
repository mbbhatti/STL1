<?php

namespace App\Controller\V1\OpenApi;

use App\Service\ApiResult;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PingController extends AbstractController
{
    /**
     * @Route("/openapi/v1/ping", name="ping", methods="GET")
     *
     * @param Connection $db
     * @return mixed
     */
    public function checkDatabaseConnection(Connection $db)
    {
        return ApiResult::success(['ok' => $db->ping()])->toHttpResponse();
    }
}

