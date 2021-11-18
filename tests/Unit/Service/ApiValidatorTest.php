<?php

namespace App\Tests\Service;

use App\Service\ApiValidator;
use App\Tests\ApiEnv\TestUtils;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiValidatorTest
 * @package App\Tests\Service
 */
class ApiValidatorTest extends TestCase
{
    public function testConstructor()
    {
        $class = 'App\Service\ApiValidator';
        $reflectedClass = new \ReflectionClass($class);
        $constructor = $reflectedClass->getConstructor();
        $this->assertEquals($class, $constructor->class);
    }

    public function testRequest()
    {
        $request = Request::create(null, 'POST', [], [], [], [], TestUtils::getPostFormBodyMock());
        $valid = new ApiValidator();
        $jsonResponse = $valid->jsonBodyOf($request);

        $this->assertEquals('Laura Pausini', $jsonResponse['artist']);
        $this->assertEquals('15', $jsonResponse['items_sold']);
        $this->assertEquals('Aufsteller', $jsonResponse['type']);

        $response = $valid->isFormValid();
        $this->assertEquals(400, $response->getCode());

        $response = $valid->isFormValid($jsonResponse);
        $this->assertEquals(200, $response->getCode());
    }

    public function testParam()
    {
        $data = ['test' => 'value'];
        $valid = new ApiValidator();
        $request = new Request($data);

        $this->assertEquals($data, $valid->getParamsOf($request));
        $this->assertEquals([], $valid->formParamsOf($request));
    }
}

