<?php

namespace App\Unit\Entity\Tests;

use App\Entity\Form;
use App\Entity\Media;
use PHPUnit\Framework\TestCase;

/**
 * Class MediaEntityTest
 * @package App\Entity\Tests
 */
class MediaEntityTest extends TestCase
{
    public function testId()
    {
        $media = new Media();
        $this->assertEquals(null, $media->getId());
    }

    public function testVersion()
    {
        $media = new Media();

        $media->setVersion('123');
        $this->assertEquals('123', $media->getVersion());

        $media->setVersion('650');
        $this->assertEquals('650', $media->getVersion());

        $this->assertNotEquals('10', $media->getVersion());
    }

    public function testType()
    {
        $media = new Media();

        $media->setType('image/jpeg');
        $this->assertEquals('image/jpeg', $media->getType());

        $media->setType('image/png');
        $this->assertEquals('image/png', $media->getType());

        $this->assertNotEquals('wrong type', $media->getType());
    }

    public function testFileName()
    {
        $media = new Media();

        $media->setFilename('lolcat.jpeg');
        $this->assertEquals('lolcat.jpeg', $media->getFilename());

        $media->setFilename('user.jpeg');
        $this->assertEquals('user.jpeg', $media->getFilename());

        $this->assertNotEquals('fail.png', $media->getFilename());
    }

    public function testThumbnail()
    {
        $media = new Media();

        $media->setThumbnail('thumb_lolcat.jpeg');
        $this->assertEquals('thumb_lolcat.jpeg', $media->getThumbnail());

        $media->setThumbnail('thumb_user.jpeg');
        $this->assertEquals('thumb_user.jpeg', $media->getThumbnail());

        $this->assertNotEquals('thumb_wrong.png', $media->getThumbnail());
    }

    public function testForm()
    {
        $media = new Media();
        $form = new Form(1);

        $media->setForm($form);
        $this->assertEquals($form, $media->getForm());

        $this->assertNotEquals('2', $media->getForm());
    }
}

