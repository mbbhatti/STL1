<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OpenApiControllerTest extends WebTestCase
{
    public function testPing()
    {
        $client = static::createClient();
        $client->request('GET', '/openapi/v1/ping');
        $response = $client->getResponse();
        $code = $response->getStatusCode();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(200, $code);
        $this->assertTrue(true, $content['ok']);
    }

    public function testMarket()
    {
        $client = static::createClient();
        $client->request('GET', '/openapi/v1/market/1234567/test.jpg');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $client->request('GET', '/openapi/v1/market/1/saturn-alex.jpg');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMedia()
    {
        $client = static::createClient();
        $client->request('GET', '/openapi/v1/media/1234567/test.jpeg');
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());

        $client->request('GET', '/openapi/v1/media/1001/lolcat.jpeg');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $client->request('GET', '/openapi/v1/media/1001/lolcat.jpeg?thumb=true');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }
}

