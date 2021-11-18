<?php

namespace App\Tests\Controller;

use App\Tests\ApiEnv\WebBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiDocControllerTest extends WebTestCase
{
    public function testApiDocs()
    {
        $client = static::createClient();
        $client->request('GET', '/api-docs');
        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $code);
        $apiResult = new WebBaseTestCase();
        $apiResult->assertHasAttributes(['info', 'servers', 'openapi', 'paths'], $content);
    }
}

