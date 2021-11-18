<?php

namespace App\Controller\V1\Api;

use App\Service\ApiResult;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\NonUniqueResultException;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/profile", name="profile", methods="GET")
     *
     * @param UserRepository $user
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getProfile(UserRepository $user)
    {
        $username = $this->getUser()->getUsername();
        $profile = $user->getUserProfile($username);

        return ApiResult::success($profile)->toHttpResponse();
    }
}

