<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiResult
 * @package App\Service
 */
class ApiResult
{
    const HTTP_OK = 200;
    const HTTP_NOT_MOFIED = 304;
    const HTTP_NOT_FOUND = 404;
    const HTTP_BAD_REQUEST = 400;

    /**
     * @var string
     */
    private $code;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $errorData;

    /**
     * ApiResult constructor.
     * @param $code
     * @param $data
     * @param $errorData
     * @param $headers
     * @internal param $message
     */
    private function __construct($code, $data, $errorData, $headers)
    {
        $this->code = $code;
        $this->data = $data;
        $this->headers = $headers;
        $this->errorData = $errorData;
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param array $data
     * @return ApiResult
     */
    public static function apiError($errorCode, $errorMessage, array $data = null): self
    {
        $errorData = ['code' => $errorCode, 'message' => $errorMessage];
        if ($data !== null) {
            $errorData['data'] = $data;
        }
        return new ApiResult(static::HTTP_BAD_REQUEST, [], $errorData, []);
    }

    /**
     * @param array $errorData
     * @param array $responseHeaders
     * @return ApiResult
     */
    public static function fail(array $errorData = [], array $responseHeaders = []): self
    {
        return new ApiResult(static::HTTP_BAD_REQUEST, [], $errorData, $responseHeaders);
    }

    /**
     * @param array $data
     * @param array $responseHeaders
     * @return ApiResult
     */
    public static function success(array $data = [], array $responseHeaders = []): self
    {
        return new ApiResult(static::HTTP_OK, $data, null, $responseHeaders);
    }

    /**
     * @return ApiResult
     */
    public static function notModified(): self
    {
        $responseHeaders = [
            'Cache-Control' => 'public',
        ];
        return new ApiResult(static::HTTP_NOT_MOFIED, [], [], $responseHeaders);
    }

    /**
     * @return ApiResult
     */
    public static function notFound(): self
    {
        return new ApiResult(static::HTTP_NOT_FOUND, [], [], []);
    }

    /**
     * @return array result body
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * For api error only, regturns the error code
     *
     * @return int error code
     */
    public function getErrorCode(): int
    {
        return ($this->getCode() !== static::HTTP_BAD_REQUEST) ? -1 : $this->errorData['code'];

    }

    /**
     * @return int Http status code
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isSuccessfull(): bool
    {
        return ($this->code === static::HTTP_OK);
    }

    /**
     * @return string result body
     */
    public function getErrorData(): string
    {
        return json_encode($this->errorData);
    }

    /**
     * @return array headers of the response
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return JsonResponse|Response
     */
    public function toHttpResponse()
    {
        switch ($this->code) {
            case static::HTTP_OK:
                return new JsonResponse($this->data, static::HTTP_OK, $this->headers);

            case static::HTTP_BAD_REQUEST:
                return new JsonResponse($this->errorData, static::HTTP_BAD_REQUEST, $this->headers);

            case static::HTTP_NOT_MOFIED:
                return new Response(null, static::HTTP_NOT_MOFIED, $this->headers);

            default:
              return new JsonResponse($this->errorData, static::HTTP_NOT_FOUND, $this->headers);
        }
    }
}

