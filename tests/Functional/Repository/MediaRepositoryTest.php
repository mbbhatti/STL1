<?php

namespace App\Tests\Functional\Repository;

use App\Entity\Form;
use App\Entity\Media;
use App\Repository\MediaRepository;
use  App\Tests\ApiEnv\TestEntityManager;
use App\Tests\ApiEnv\TestUtils;

/**
 * Class MediaRepositoryTest
 * @package App\Tests\Functional\Repository
 */
class MediaRepositoryTest extends TestEntityManager
{
    public function testConstrctor()
    {
        $kernel = self::bootKernel();
        $managerRegistry = $kernel->getContainer()->get('doctrine');
        $formRespository = $this->entityManager->getRepository(Form::class);
        $response = new MediaRepository($managerRegistry, $formRespository);

        $this->assertNotEmpty($response);
    }

    public function testMediaEtag()
    {
        $userId = 2;
        $etag = $this->entityManager->getRepository(Media::class)->getMediaEtag($userId);
        if (empty($etag)) {
            $this->assertEmpty($etag);
        } else {
            $testUtil = new TestUtils();
            $media = $testUtil->getMediaUpdatedAt($this->entityManager);
            $this->assertArrayHasKey('etag', $etag[0]);
            $this->assertGreaterThanOrEqual($etag[0]['etag'], $media[0]['etag']);
        }

        $userId = 123456;
        $etag = $this->entityManager->getRepository(Media::class)->getMediaEtag($userId);
        $this->assertEmpty($etag);
    }
}

