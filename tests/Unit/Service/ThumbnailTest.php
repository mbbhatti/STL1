<?php

namespace App\Tests\Service;

use App\Tests\ApiEnv\TestUtils;
use App\Service\ImageThumbnail;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class ThumbnailTest
 * @package App\Tests\Service
 */
class ThumbnailTest extends TestCase
{
    public function testImagePath()
    {
        $service = TestUtils::getService();
        $crudLex = $service->getData('media');
        $entity = $crudLex->get(1001);

        $image = new ImageThumbnail();
        $this->assertNotEmpty($image->getImagePath($entity));
    }

    public function testThumbnailPath()
    {
        $service = TestUtils::getService();
        $crudLex = $service->getData('media');
        $entity = $crudLex->get(1180);

        $image = new ImageThumbnail();
        $this->assertNotEmpty($image->getThumbnailPath($entity));
    }

    public function testCreateThumbnail()
    {
        $service = TestUtils::getService();
        $crudLex = $service->getData('media');
        $entity = $crudLex->get(1429);

        $image = new ImageThumbnail();
        $path = $image->getImagePath($entity);
        $fileSystem = TestUtils::getAwsConfiguration(true)->getFileSystem();
        $imagePath = $fileSystem->read($path);

        $reflection = new ReflectionClass(get_class($image));
        $method = $reflection->getMethod('thumbnailFromImage');
        $method->setAccessible(true);
        $thumbnail = $method->invokeArgs($image, [$imagePath]);
        $this->assertNotNull($thumbnail);
    }

    public function testCreateThumbnailIfNotExists()
    {
        $service = TestUtils::getService();
        $crudLex = $service->getData('media');
        $entity = $crudLex->get(1429);

        $image = new ImageThumbnail();
        $fileSystem = TestUtils::getAwsConfiguration(true)->getFileSystem();

        $response = $image->createThumbnailIfNotExists($entity, $service, $fileSystem, true);
        $this->assertEquals(null, $response);

        $response = $image->createThumbnailIfNotExists($entity, $service, $fileSystem);
        $this->assertEquals(null, $response);
    }
}

