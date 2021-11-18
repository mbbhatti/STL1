<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Valdi\RulesBuilder;
use Valdi\Validator;

/**
 * Class ApiValidator
 * @package App\Service
 */
class ApiValidator
{
    const GET_PARAMS = 1;
    const JSON_BODY = 2;
    const FORM_DATA = 3;

    /**
     * @var Validator
     */
    private $valid;

    /**
     * ApiValidator constructor.
     */
    public function __construct()
    {
        $this->valid = new Validator();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function jsonBodyOf(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getParamsOf(Request $request): array
    {
        return $request->query->all();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function formParamsOf(Request $request): array
    {
        return $request->request->all();
    }

    /**
     * @param array|null $data
     * @return ApiResult
     */
    public function isFormValid(array $data = null): ApiResult
    {
        $builder = RulesBuilder::create();
        $required = [
            'start_at',
            'end_at',
            'artist',
            'action',
            'placement',
            'type',
            'market_id',
            'items_sold',
            'items_amount',
        ];

        foreach ($required as $key) {
            $builder->addRule($key, 'required');
        }

        return $this->checkForInvalidData($builder->getRules(), $data);
    }

    /**
     * @param $rules
     * @param array|null $data
     * @return ApiResult
     */
    private function checkForInvalidData($rules, array $data = null): ApiResult
    {
        if ($data === null) {
            $data = [];
        }

        $validation = $this->valid->isValid($rules, $data);
        if ($validation['valid'] === false) {
            return ApiResult::apiError(1, 'Invalid parameters', $validation['errors']);
        } else {
            return ApiResult::success();
        }
    }
}

