<?php

namespace App\Controller\V1\Api;

use App\Service\ApiResult;
use App\Util\EtagEntity;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SuggestionController extends AbstractController
{
    /**
     * @Route("/api/v1/suggestion", name="suggestion", methods="GET")
     *
     * @param Request $request
     * @param SuggestionRepository $suggestion
     * @param EtagEntity $etag
     * @return mixed
     */
    public function getSuggestion(Request $request, SuggestionRepository $suggestion, EtagEntity $etag)
    {
        $suggestionEtag = $suggestion->getSuggestionEtag();
        $etagHeaders = $etag->headersWithEtag($suggestionEtag);

        if ($etag->notModifiedSince($request, $etagHeaders)) {
            $apiResult = ApiResult::notModified();
        } else {
            $suggestions = $suggestion->getSuggestions();
            $apiResult = ApiResult::success($suggestions, $etagHeaders);
        }

        return $apiResult->toHttpResponse();
    }
}

