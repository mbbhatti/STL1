<?php

namespace App\Unit\Entity\Tests;

use App\Entity\Form;
use App\Entity\Market;
use App\Entity\MarketGroup;
use App\Entity\Media;
use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class FormEntityTest
 * @package App\Entity\Tests
 */
class FormEntityTest extends TestCase
{
    public function testId()
    {
        $form = new Form();
        $this->assertEquals(null, $form->getId());
    }

    public function testVersion()
    {
        $form = new Form();

        $form->setVersion('191');
        $this->assertEquals('191', $form->getVersion());

        $this->assertNotEquals('205', $form->getVersion());
    }

    public function testItemsSold()
    {
        $form = new Form();

        $form->setItemsSold(15);
        $this->assertEquals(15, $form->getItemsSold());

        $form->setItemsSold(0);
        $this->assertEquals(0, $form->getItemsSold());

        $this->assertNotEquals(10, $form->getItemsSold());
    }

    public function testItemsAmount()
    {
        $form = new Form();

        $form->setItemsAmount(40);
        $this->assertEquals(40, $form->getItemsAmount());

        $form->setItemsAmount(25);
        $this->assertEquals(25, $form->getItemsAmount());

        $this->assertNotEquals(0, $form->getItemsAmount());
    }

    public function testType()
    {
        $form = new Form();

        $form->setType('Aufsteller');
        $this->assertEquals('Aufsteller', $form->getType());

        $form->setType('omerrrre');
        $this->assertEquals('omerrrre', $form->getType());

        $this->assertNotEquals('Eine Testart der Platzierung 99', $form->getType());
    }

    public function testPlacement()
    {
        $form = new Form();

        $form->setPlacement('1. Etage');
        $this->assertEquals('1. Etage', $form->getPlacement());

        $form->setPlacement('OrtTest99');
        $this->assertEquals('OrtTest99', $form->getPlacement());

        $this->assertNotEquals('test', $form->getPlacement());
    }

    public function testAction()
    {
        $form = new Form();

        $form->setAction('1. Christmas 2016');
        $this->assertEquals('1. Christmas 2016', $form->getAction());

        $form->setAction('90% teurer');
        $this->assertEquals('90% teurer', $form->getAction());

        $this->assertNotEquals('Action 2017-04-06', $form->getAction());
    }

    public function testArtist()
    {
        $form = new Form();

        $form->setArtist('Laura Pausini');
        $this->assertEquals('Laura Pausini', $form->getArtist());

        $form->setArtist('ZusatzinfoUndSo');
        $this->assertEquals('ZusatzinfoUndSo', $form->getArtist());

        $this->assertNotEquals('-', $form->getArtist());
    }

    public function testStartAt()
    {
        $form = new Form();

        $current = new DateTime();
        $form->setStartAt($current);
        $this->assertEquals($current, $form->getStartAt());

        $date = new DateTime('2019-07-16 07:52:00');
        $form->setStartAt($date);
        $this->assertEquals($date, $form->getStartAt());
    }

    public function testEndAt()
    {
        $form = new Form();

        $current = new DateTime();
        $form->setEndAt($current);
        $this->assertEquals($current, $form->getEndAt());

        $date = new DateTime('2016-12-09 16:27:53');
        $form->setEndAt($date);
        $this->assertEquals($date, $form->getEndAt());
    }

    public function testMarketName()
    {
        $form = new Form();

        $form->setMarketName('Bielefeld SAT');
        $this->assertEquals('Bielefeld SAT', $form->getMarketName());

        $form->setMarketName('Bln Alex SAT');
        $this->assertEquals('Bln Alex SAT', $form->getMarketName());

        $this->assertNotEquals('Bielefeld MM', $form->getMarketName());
    }

    public function testCustomerId()
    {
        $form = new Form();

        $form->setCustomerId('395034');
        $this->assertEquals('395034', $form->getCustomerId());

        $this->assertNotEquals('476223', $form->getCustomerId());
    }

    public function testMedia()
    {
        $form = new Form();
        $media = new Media('1');

        $form->addMedia($media);
        $this->assertEquals(true, $form->removeMedia($media));
        $this->assertEmpty($form->getMedias());
    }

    public function testUsers()
    {
        $form = new Form();
        $user = new User('ADMIN');

        $form->setUser($user);
        $this->assertEquals($user, $form->getUser());

        $this->assertNotEquals('TEST', $form->getUser());
    }

    public function testMarket()
    {
        $form = new Form();
        $market = new Market(1);

        $form->setMarket($market);
        $this->assertEquals($market, $form->getMarket());

        $this->assertNotEquals('2', $form->getMarket());
    }

    public function testGroup()
    {
        $form = new Form();
        $marketGroup = new MarketGroup(1);

        $form->setGroup($marketGroup);
        $this->assertEquals($marketGroup, $form->getGroup());

        $this->assertNotEquals('2', $form->getGroup());
    }
}

