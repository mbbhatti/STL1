<?php

namespace App\Tests\Util;

use App\Util\PosEntity;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Class PosEntityTest
 * @package App\Tests\Util
 */
class PosEntityTest extends TestCase
{
    public function testPos()
    {
        $tests = [
            [
                "host" => "localhost",
                'port' => 8000,
                'route' => "marketMedia",
                'attributes' => [
                    "id" => 1,
                    "filename" => 'saturn-alex.jpg'
                ],
                'rows' => [
                    "id" => 1,
                    "customer_id" => 176212,
                    "name" => "Bln Alex SAT",
                    "image" => 'saturn-alex.jpg',
                    "marketMedia" => 1
                ]
            ],
            [
                "host" => "localhost",
                'port' => 8000,
                'route' => "marketMedia",
                'attributes' => [
                    "id" => 28,
                    "filename" => null
                ],
                'rows' => [
                    "id" => 28,
                    "customer_id" => 176284,
                    "name" => "Rostock SAT",
                    "image" => null,
                    "marketMedia" => 28
                ]
            ],
            [
                "host" => "testhost",
                'port' => 8000,
                'route' => "marketMedia",
                'attributes' => [
                    'id' => 29,
                    'filename' =>  'lolcat.jpeg'
                ],
                'rows' => [
                    "id" => 29,
                    "customer_id" => 195052,
                    "name" => "Sievershagen MM",
                    "image" => null,
                    "marketMedia" => 29
                ]
            ],
            [
                "host" => "testhost",
                'port' => 8000,
                'route' => "marketMedia",
                'attributes' => [
                    'id' => 123456789,
                    'filename' =>  null
                ],
                'rows' => [
                    "id" => 123456789,
                    "customer_id" => 123456789,
                    "name" => "Name last two char mismatches",
                    "image" => null,
                    "marketMedia" => 123456789
                ]
            ],
            [
                "host" => "testhost",
                'port' => 8000,
                'route' => "marketMedia",
                'attributes' => [
                    'id' => 1234567890,
                    'filename' =>  'lolcat.jpeg'
                ],
                'rows' => [
                    "id" => 1234567890,
                    "customer_id" => 1234567890,
                    "name" => "Image is not empty",
                    "image" => 'lolcat.jpeg',
                    "marketMedia" => 1234567890
                ]
            ],
            [
                "host" => "testhost",
                'port' => 8000,
                'route' => "marketMedia",
                'attributes' => [
                    'id' => 12345678,
                    'filename' =>  'lolcat.jpeg'
                ],
                'rows' => [
                    "id" => 12345678,
                    "url" => 12345678,
                    "customer_id" => 12345678,
                    "name" => "Image and filename is not empty and have url",
                    "image" => 'lolcat.jpeg',
                    "filename" => 'lolcat.jpeg',
                    "marketMedia" => 12345678
                ]
            ],
        ];

        foreach ($tests as $test) {
            $mock = $this->getMockBuilder(RouterInterface::class)->getMock();
            $requestContext = new RequestContext();
            $requestContext->setHost($test['host']);
            $requestContext->setHttpPort($test['port']);
            $mock->method('getContext')->willReturn($requestContext);
            $mock->method('generate')->with($test['route'], $test['attributes']);

            $response = new PosEntity($mock);
            $rowsContent = $response->urlMapper($test['rows']);
            $this->assertEquals($test['rows']['id'], $rowsContent['id']);
        }

    }
}

