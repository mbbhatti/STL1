<?php

namespace App\Tests\Functional\Repository;

use App\Entity\User;
use  App\Tests\ApiEnv\TestEntityManager;
use App\Tests\ApiEnv\WebBaseTestCase;

/**
 * Class UserRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class UserRepositoryTest extends TestEntityManager
{
    public function testUserProfile()
    {
        $username = 'admin';
        $profile = $this->entityManager->getRepository(User::class)->getUserProfile($username);
        $apiResult = new WebBaseTestCase();
        $apiResult->assertHasAttributes(['id', 'username', 'email'], $profile);

        $username = 'fakeUser';
        $profile = $this->entityManager->getRepository(User::class)->getUserProfile($username);
        $this->assertNull($profile);
    }

    public function testUserEtag()
    {
        $username = 'admin';
        $etag = $this->entityManager->getRepository(User::class)->getUserEtag($username);
        $this->assertArrayHasKey('etag', $etag[0]);
        $this->assertNotNull($etag[0]['etag']);

        $username = 'unknowUser';
        $etag = $this->entityManager->getRepository(User::class)->getUserEtag($username);
        $this->assertArrayHasKey('etag', $etag[0]);
        $this->assertContains($etag[0]['etag'], [null]);
    }

    public function testUserMarketGroup()
    {
        $username = 'karmic';
        $user = $this->entityManager->getRepository(User::class)->getUserMarketGroup($username);
        $this->assertArrayHasKey('ids', $user[0]);
        $this->assertNotNull($user[0]['ids']);

        $username = 'dummyUser';
        $user = $this->entityManager->getRepository(User::class)->getUserMarketGroup($username);
        $this->assertArrayHasKey('ids', $user[0]);
        $this->assertEquals(null, $user[0]['ids']);
    }
}

