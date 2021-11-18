<?php

namespace App\Tests\Unit\Service;

use App\Tests\ApiEnv\TestUtils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\Exception;
use Throwable;
use Sentry;

/**
 * Class ConfigurationTest
 * @package App\Tests\Unit\Service
 */
class ConfigurationTest extends TestCase
{
    public function testAws()
    {
        $aws = TestUtils::getAwsConfiguration(false);
        $this->assertNotEmpty($aws->getFileSystem());

        $aws = TestUtils::getAwsConfiguration(true);
        $this->assertNotEmpty($aws->getFileSystem());
    }

    public function testSentry()
    {
        try {
            throw new Exception('sentry');
        } catch (Throwable $exception) {
            $response = Sentry\captureException($exception);
            if($response == null) {
                $this->assertEquals(null, $response);
            } else {
                $this->assertNotEmpty($response);
            }
        }
    }
}

