<?php

namespace App\Controller\V1\Api;

use App\Repository\UserRepository;
use App\Repository\MarketRepository;
use App\Service\ApiResult;
use App\Util\EtagEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MarketController extends AbstractController
{
    /**
     * @Route("/api/v1/market", name="market", methods="GET")
     *
     * @param Request $request
     * @param EtagEntity $etag
     * @param UserRepository $user
     * @param MarketRepository $market
     * @return mixed
     */
    public function getMarket(Request $request, EtagEntity $etag, UserRepository $user, MarketRepository $market)
    {
        $username = $this->getUser()->getUsername();
        $etags = array_merge($market->getMarketEtag(), $user->getUserEtag($username));
        $etagHeaders = $etag->headersWithEtag($etags);

        if ($etag->notModifiedSince($request, $etagHeaders)) {
            $apiResult = ApiResult::notModified();
        } else {
            $markets = $market->getMarkets($username);
            $apiResult = ApiResult::success($markets, $etagHeaders);
        }

        return $apiResult->toHttpResponse();
    }
}

