<?php

namespace App\Unit\Entity\Tests;

use App\Entity\Form;
use App\Entity\Market;
use App\Entity\MarketGroup;
use PHPUnit\Framework\TestCase;

/**
 * Class MarketEntityTest
 * @package App\Entity\Tests
 */
class MarketEntityTest extends TestCase
{
    public function testId()
    {
        $market = new Market();

        $market->setId('100');
        $this->assertEquals('100', $market->getId());
    }

    public function testName()
    {
        $market = new Market();

        $market->setName('Bln Alex SAT');
        $this->assertEquals('Bln Alex SAT', $market->getName());

        $this->assertNotEquals('My Name', $market->getName());
    }

    public function testVersion()
    {
        $market = new Market();

        $market->setVersion('23');
        $this->assertEquals('23', $market->getVersion());

        $this->assertNotEquals('190', $market->getVersion());
    }

    public function testCustomerId()
    {
        $market = new Market();

        $market->setCustomerId('176212');
        $this->assertEquals('176212', $market->getCustomerId());

        $this->assertNotEquals('276228', $market->getCustomerId());
    }

    public function testEcrId()
    {
        $market = new Market();

        $market->setEcrId('2M');
        $this->assertEquals('2M', $market->getEcrId());

        $market->setEcrId('2L');
        $this->assertEquals('2L', $market->getEcrId());

        $this->assertNotEquals('22X', $market->getEcrId());
    }

    public function testCity()
    {
        $market = new Market();

        $market->setCity('Berlin');
        $this->assertEquals('Berlin', $market->getCity());

        $market->setCity('Bremen');
        $this->assertEquals('Bremen', $market->getCity());

        $this->assertNotEquals('Hamburg', $market->getCity());
    }

    public function testStreet()
    {
        $market = new Market();

        $market->setStreet('Oppelner Str.213');
        $this->assertEquals('Oppelner Str.213', $market->getStreet());

        $market->setStreet('Münchner Str.173');
        $this->assertEquals('Münchner Str.173', $market->getStreet());

        $this->assertNotEquals('Warrington Platz 10', $market->getStreet());
    }

    public function testZipCode()
    {
        $market = new Market();

        $market->setZipcode('90473');
        $this->assertEquals('90473', $market->getZipcode());

        $market->setZipcode('85435');
        $this->assertEquals('85435', $market->getZipcode());

        $this->assertNotEquals('90762', $market->getZipcode());
    }

    public function testCeo()
    {
        $market = new Market();

        $market->setCeo('Herr Dabestani');
        $this->assertEquals('Herr Dabestani', $market->getCeo());

        $market->setCeo('Wolfgang Bachesz');
        $this->assertEquals('Wolfgang Bachesz', $market->getCeo());

        $this->assertNotEquals('Herr Schuhladen', $market->getCeo());
    }

    public function testDirector()
    {
        $market = new Market();

        $market->setDirector('Hakan Demirkesen');
        $this->assertEquals('Hakan Demirkesen', $market->getDirector());

        $this->assertNotEquals('Kati Benner', $market->getDirector());
    }

    public function testDispatcher()
    {
        $market = new Market();

        $market->setDispatcher('Christoph Hobein');
        $this->assertEquals('Christoph Hobein', $market->getDispatcher());

        $market->setDispatcher('');
        $this->assertEquals('', $market->getDispatcher());

        $this->assertNotEquals('Melanie Promeuschel', $market->getDispatcher());
    }

    public function testPhone()
    {
        $market = new Market();

        $market->setPhone('o89/41317217');
        $this->assertEquals('o89/41317217', $market->getPhone());

        $market->setPhone('09131-9053137');
        $this->assertEquals('09131-9053137', $market->getPhone());

        $this->assertNotEquals('No Phone', $market->getPhone());
    }

    public function testImage()
    {
        $market = new Market();

        $market->setImage('null');
        $this->assertEquals('null', $market->getImage());

        $this->assertNotEquals('', $market->getImage());
    }

    public function testFieldWorker()
    {
        $market = new Market();

        $market->setFieldWorker('Michael Schmugler');
        $this->assertEquals('Michael Schmugler', $market->getFieldWorker());

        $market->setFieldWorker('Peter Grimmbacher');
        $this->assertEquals('Peter Grimmbacher', $market->getFieldWorker());

        $this->assertNotEquals('', $market->getFieldWorker());
    }

    public function testGroup()
    {
        $market = new Market();
        $group = new MarketGroup(11);

        $market->setGroup($group);
        $this->assertEquals($group, $market->getGroup());

        $this->assertNotEquals('object', $market->getGroup());
    }

    public function testForm()
    {
        $market = new Market();
        $form = new Form(1);

        $market->addForm($form);
        $this->assertEquals(true, $market->removeForm($form));
        $this->assertEmpty($market->getForms());
    }
}

