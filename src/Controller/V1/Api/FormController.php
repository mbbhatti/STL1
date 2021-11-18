<?php

namespace App\Controller\V1\Api;

use App\Service\FormService;
use App\Service\ApiResult;
use App\Service\ApiValidator;
use App\Service\Configuration;
use CRUDlex\Service;
use App\Util\EtagEntity;
use CRUDlex\FileHandler;
use App\Repository\FormRepository;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use App\Repository\MarketRepository;
use App\Repository\SuggestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NoResultException;

class FormController extends AbstractController
{
    /**
     * @Route("/api/v1/form", name="form", methods="GET")
     *
     * @param Request $request
     * @param UserRepository $user
     * @param FormRepository $form
     * @param MediaRepository $media
     * @param EtagEntity $etag
     * @param FormService $formService
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getForm(
        Request $request,
        UserRepository $user,
        FormRepository $form,
        MediaRepository $media,
        EtagEntity $etag,
        FormService $formService
    )
    {
        $username = $this->getUser()->getUsername();
        $userData = $user->getUserProfile($username);
        $userId = $userData['id'];
        $etags = array_merge(
            $form->getFormEtag($userId),
            $media->getMediaEtag($userId)
        );

        $etagHeaders = $etag->headersWithEtag($etags);
        if ($etag->notModifiedSince($request, $etagHeaders)) {
            $apiResult = ApiResult::notModified();
        } else {
            $forms = $formService->getAllUserForms($userId);
            $apiResult = ApiResult::success($forms, $etagHeaders);
        }

        return $apiResult->toHttpResponse();
    }

    /**
     * @Route("/api/v1/form", name="uploadForm", methods="POST")
     *
     * @param Request $request
     * @param ApiValidator $valid
     * @param MarketRepository $market
     * @param FormService $formService
     * @param UserRepository $user
     * @param SuggestionRepository $suggestion
     * @return mixed
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function uploadForm(
        Request $request,
        ApiValidator $valid,
        MarketRepository $market,
        FormService $formService,
        UserRepository $user,
        SuggestionRepository $suggestion
    )
    {
        $params = $valid->jsonBodyOf($request);
        $validation = $valid->isFormValid($params);
        if (!$validation->isSuccessfull()) {
            return $validation->toHttpResponse();
        }

        // Fetch market information
        $data = $market->isMarketExist($params['market_id']);
        if ($data === null) {
            return ApiResult::apiError(
                120,
                'Invalid market ' . $params['market_id']
            )->toHttpResponse();
        }

        // Save form and get response
        $username = $this->getUser()->getUsername();
        $userData = $user->getUserProfile($username);
        $formData = $formService->insertForm($userData['id'], $params, $data);

        // Manage suggestion
        $attributes = ['artist', 'action', 'department', 'type'];
        $suggestions = array_map(function ($attribute) use ($params) {
            // Take care of the naming mismatch between the post request and the suggestions endpoint
            $value = $attribute == 'department' ? $params['placement'] : $params[$attribute];

            return [$attribute, $value];
        }, $attributes);

        // Inserting suggestion
        $suggestion->insertSuggestion($suggestions, false);

        // Return and set success form response
        return ApiResult::success($formData)->toHttpResponse();
    }

    /**
     * @Route("/api/v1/form/{form}/media", name="uploadMedia", methods="POST")
     *
     * @param Request $request
     * @param FormRepository $frm
     * @param Service $service
     * @param Configuration $config
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function uploadMedia(
        Request $request,
        FormRepository $frm,
        Service $service,
        Configuration $config
    )
    {
        $entityName = 'media';
        $form = $request->get('form');
        $username = $this->getUser()->getUsername();

        // Check form id exist for this user
        if ($frm->isFormExist($form, $username) == 0) {
            return ApiResult::apiError(
                10,
                'Invalid form id=' . $form
            )->toHttpResponse();
        }

        // Check file found
        if ($request->files->count() == 0) {
            return ApiResult::apiError(
                20,
                'Expected a Multipart POST request with a file'
            )->toHttpResponse();
        }

        // Get the last file
        $files = $request->files->all();
        $file = array_pop($files);

        // Filename used as crudlex db attribute name
        $request->files->set('filename', $file);

        // Check file type
        $type = $file->getMimeType();
        $isImage = strpos($type, 'image/') === 0;
        $isVideo = strpos($type, 'video/') === 0;
        if (!$isImage && !$isVideo) {
            return ApiResult::apiError(
                21,
                'Expected a file with mimetype image/* or video/*'
            )->toHttpResponse();
        }

        // Create crudLex entity
        $crudLex = $service->getData($entityName);
        $entity = $crudLex->createEmpty();
        $entity->set('filename', $file->getClientOriginalName());
        $entity->set('type', $type);
        $entity->set('form', $form);
        $crudLex->create($entity);

        // Manage files
        $fileHandler = new FileHandler($config->getFileSystem(), $crudLex->getDefinition());
        $fileHandler->updateFiles($crudLex, $request, $entity, $entityName);

        // Get form response by id
        $formData = $frm->getFormById($form);

        return ApiResult::success($formData)->toHttpResponse();
    }
}

