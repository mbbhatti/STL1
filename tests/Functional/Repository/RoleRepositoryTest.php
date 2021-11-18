<?php

namespace App\Tests\Functional\Repository;

use App\Repository\RoleRepository;
use App\Tests\ApiEnv\TestEntityManager;

/**
 * Class RoleRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class RoleRepositoryTest extends TestEntityManager
{
    public function testConstrctor()
    {
        $kernel = self::bootKernel();
        $managerRegistry = $kernel->getContainer()->get('doctrine');
        $response = new RoleRepository($managerRegistry);

        $this->assertNotEmpty($response);
    }
}

