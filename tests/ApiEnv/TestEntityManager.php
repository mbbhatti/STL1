<?php

namespace App\Tests\ApiEnv;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TestEntityManager
 * @package App\Tests\ApiEnv
 */
class TestEntityManager extends KernelTestCase
{
    public $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}

