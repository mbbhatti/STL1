<?php

namespace App\Tests\Functional\Repository;

use App\Repository\MarketGroupRepository;
use App\Tests\ApiEnv\TestEntityManager;

/**
 * Class MarketGroupRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class MarketGroupRepositoryTest extends TestEntityManager
{
    public function testConstrctor()
    {
        $kernel = self::bootKernel();
        $managerRegistry = $kernel->getContainer()->get('doctrine');
        $response = new MarketGroupRepository($managerRegistry);

        $this->assertNotEmpty($response);
    }
}

