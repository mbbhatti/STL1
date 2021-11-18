<?php

namespace App\Tests\Util;

use App\Util\DBEntity;
use App\Tests\ApiEnv\TestUtils;
use PHPUnit\Framework\TestCase;
use DateTime;

/**
 * Class DBEntityTest
 * @package App\Tests\Util
 */
class DBEntityTest extends TestCase
{
    public function testUnsetAttributes()
    {
        $params = [
            'username' => 'hello',
            'name' => 'ABC'
        ];

        DBEntity::unsetAttributes($params, ['name']);
        $this->assertEquals(['username'=> 'hello'], $params);

        DBEntity::unsetAttributes($params, ['username']);
        $this->assertEquals([], $params);
    }

    public function testHierarchy()
    {
        $dbEntity = new DBEntity();
        $hierarchy = $dbEntity->toHierarchy(
            TestUtils::getDBMockData(),
            1,
            'children',
            'id'
        );

        $this->assertCount(4, $hierarchy);
    }

    public function testApiMapper()
    {
        $data = [
            [
                'ok' => true,
                'id' => 1,
                'created_at' => new DateTime(),
                'start_at' => new DateTime(),
                'end_at' => '2020-06-10 10:22:30',
            ]
        ];

        $dbEntity = new DBEntity();
        $result = array_map($dbEntity->mysql2ApiMapper(), $data);
        $response = $dbEntity->createMapper($result);
        $this->assertNotEmpty($response);
    }

    public function testMapper()
    {
        $mapper = [
            'boolean' => ['ok'],
            'integer' => ['id'],
            'date' => ['created_at', 'start_at', 'end_at'],
            'float' => ['average'],
            'json' => ['product']
        ];

        $data = [
            [
                'ok' => true,
                'id' => 1,
                'created_at' => new DateTime(),
                'start_at' => new DateTime(),
                'end_at' => '2020-06-10 10:22:30',
                'average' => 12.31,
                'product' => json_encode('12')
            ]
        ];

        $dbEntity = new DBEntity();
        $mapperResponse = $dbEntity->createMapper($mapper);
        $response = array_map($mapperResponse, [false]);
        $this->assertNull($response[0]);

        $response = array_map($mapperResponse, $data);
        $this->assertNotEmpty($response);
    }

    public function testFirtsColumn()
    {
        $dbEntity = new DBEntity();
        $this->assertCount(
            4,
            array_map(
                $dbEntity->firstColumn(),
                TestUtils::getDBMockData()
            )
        );
    }

    public function testStandardDBParams()
    {
        $dbEntity = new DBEntity();
        $param = $dbEntity->standardDbParams();

        $this->assertEquals(1, $param['version']);
        $this->assertEquals(null, $param['deletedAt']);
    }
}

