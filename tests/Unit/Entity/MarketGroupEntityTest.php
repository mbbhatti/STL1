<?php

namespace App\Unit\Entity\Tests;

use App\Entity\Market;
use App\Entity\Form;
use App\Entity\User;
use App\Entity\MarketGroup;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class MarketGroupEntityTest
 * @package App\Entity\Tests
 */
class MarketGroupEntityTest extends TestCase
{
    public function testId()
    {
        $marketGroup = new MarketGroup();

        $marketGroup->setId('92');
        $this->assertEquals('92', $marketGroup->getId());
    }

    public function testVersion()
    {
        $marketGroup = new MarketGroup();

        $marketGroup->setVersion('412');
        $this->assertEquals('412', $marketGroup->getVersion());

        $this->assertNotEquals('10', $marketGroup->getVersion());
    }

    public function testSr()
    {
        $marketGroup = new MarketGroup();

        $marketGroup->setSr('2A');
        $this->assertEquals('2A', $marketGroup->getSr());

        $marketGroup->setSr('2W');
        $this->assertEquals('2W', $marketGroup->getSr());

        $this->assertNotEquals('2Test', $marketGroup->getSr());
    }

    public function testDisplay()
    {
        $marketGroup = new MarketGroup();

        $marketGroup->setDisplay('');
        $this->assertEquals('', $marketGroup->getDisplay());

        $this->assertNotEquals(' ', $marketGroup->getDisplay());
    }

    public function testUsers()
    {
        $marketGroup = new MarketGroup();

        $users = ['Philip','Thomas'];
        $marketGroup->setUsers(new ArrayCollection($users));
        $this->assertEquals($users, $marketGroup->getUsers());
        $this->assertNotEquals(['Me'], $marketGroup->getUsers());

        $user = new User(1);
        $marketGroup->addUser($user);
        $this->assertEquals(true, $marketGroup->removeUser($user));
    }

    public function testMarket()
    {
        $marketGroup = new MarketGroup();
        $market = new Market(1);

        $marketGroup->addMarket($market);
        $this->assertEquals(true, $marketGroup->removeMarket($market));
        $this->assertEmpty($marketGroup->getMarkets());
    }

    public function testForm()
    {
        $marketGroup = new MarketGroup();
        $form = new Form(1);

        $marketGroup->addForm($form);
        $this->assertEquals(true, $marketGroup->removeForm($form));
        $this->assertEmpty($marketGroup->getForms());
    }
}

