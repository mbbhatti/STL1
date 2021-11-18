<?php

namespace App\Tests\Service;

use App\Service\ApiResult;
use App\Tests\ApiEnv\WebBaseTestCase;

/**
 * Class ApiResultTest
 * @package App\Tests\Service
 */
class ApiResultTest extends WebBaseTestCase
{
    public function testSuccessAssertions()
    {
        $this->assertApiSuccess('Success', ApiResult::success());
        $this->assertApiSuccess('Success', ApiResult::success(['ok' => true]));
    }

    public function testErrorAssertions()
    {
        $this->assertApiError('ApiError', ApiResult::apiError(100, 'bla bla'));
        $this->assertApiError('ApiError', ApiResult::apiError(100, 'bla bla', ['Test']), 100);
    }

    public function testFailAssertions()
    {
        $this->assertApiFail('ApiFail', ApiResult::fail());
        $this->assertApiFail('ApiFail', ApiResult::fail(array('fail')), 400);
    }

    public function testNotModifiedAssertions()
    {
        $this->assertEquals(304, ApiResult::notModified()->getCode(), 'Not modified error code');
    }

    public function testBaicMethods()
    {
        $data = ['ok' => true];
        $header = ['json'];

        $response = ApiResult::success($data, $header);
        $this->assertEquals($data, $response->getData());
        $this->assertEquals($header, $response->getHeaders());
        $this->assertEquals(200, $response->getCode());
        $this->assertEquals(-1, $response->getErrorCode());
        $this->assertEquals('null', $response->getErrorData());
        $this->assertEquals(true, $response->isSuccessfull());
    }

    public function testToHttpResponse()
    {
        $success = ApiResult::success(['ok' => true]);
        $this->assertEquals(200, $success->toHttpResponse()->getStatusCode());

        $error = ApiResult::apiError(100, 'bla bla', ['Test code']);
        $this->assertEquals(400, $error->toHttpResponse()->getStatusCode());

        $found = ApiResult::notModified();
        $this->assertEquals(304, $found->toHttpResponse()->getStatusCode());

        $found = ApiResult::notFound();
        $this->assertEquals(404, $found->toHttpResponse()->getStatusCode());
    }
}

