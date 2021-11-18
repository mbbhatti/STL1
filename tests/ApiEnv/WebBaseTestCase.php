<?php

namespace App\Tests\ApiEnv;

use PHPUnit\Framework\TestCase;
use App\Service\ApiResult;

/**
 * Class WebBaseTestCase
 * @package App\Tests\ApiEnv
 */
class WebBaseTestCase extends TestCase
{
    /**
     * @param $expected
     * @param $actual
     */
    public function assertHasAttributes($expected, $actual)
    {
        $diff = array_diff($expected, array_keys($actual));
        $message = 'Missing keys: '
            . json_encode(array_values($diff))
            . ' Actual keys: '
            . json_encode(array_keys($actual));
        $this->assertEmpty($diff, $message);
    }

    /**
     * @param $name
     * @param ApiResult $result
     * @param null $expected
     */
    public function assertApiError($name, ApiResult $result, $expected = null)
    {
        $ok = $result->getCode() == ApiResult::HTTP_BAD_REQUEST;
        $this->assertTrue(
            $ok,
            $name . ' => Expected HTTP_BAD_REQUEST, got HTTP ' . $result->getCode()
        );

        if ($expected !== null) {
            $this->assertEquals(
                $expected,
                $result->getErrorCode(),
                $name . ' => error code not the right one'
            );
        }
    }

    /**
     * @param $name
     * @param ApiResult $result
     */
    public function assertApiSuccess($name, ApiResult $result)
    {
        $this->assertTrue($result->isSuccessfull(), $name
            . ' => Expected api success, got HTTP ' . $result->getCode()
            . ' data=' . $result->getErrorData());

    }

    /**
     * @param $name
     * @param ApiResult $result
     * @param null $expected
     */
    public function assertApiFail($name, ApiResult $result, $expected = null)
    {
        $ok = $result->getCode() == ApiResult::HTTP_BAD_REQUEST;
        $this->assertTrue(
            $ok,
            $name . ' => Expected HTTP_BAD_REQUEST, got HTTP ' . $result->getCode()
        );

        if ($expected !== null) {
            $this->assertEquals(
                $expected,
                $result->getCode(),
                $name . ' => error code not the right one'
            );
        }
    }
}

