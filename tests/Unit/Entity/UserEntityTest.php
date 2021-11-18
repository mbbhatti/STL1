<?php

namespace App\Unit\Entity\Tests;

use App\Entity\Form;
use App\Entity\MarketGroup;
use App\Entity\Role;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class UserEntityTest
 * @package App\Entity\Tests
 */
class UserEntityTest extends TestCase
{
    public function testId()
    {
        $user = new User();
        $this->assertEquals(null, $user->getId());
    }

    public function testUsername()
    {
        $user = new User();

        $user->setUsername('admin');
        $this->assertEquals('admin', $user->getUsername());

        $user->setUsername('test');
        $this->assertEquals('test', $user->getUsername());

        $this->assertNotEquals('fail', $user->getUsername());
        $this->assertNotEquals('wrong', $user->getUsername());
    }

    public function testEmail()
    {
        $user = new User();

        $user->setEmail('admin@smf.com');
        $this->assertEquals('admin@smf.com', $user->getEmail());

        $user->setEmail('test@smf.com');
        $this->assertEquals('test@smf.com', $user->getEmail());

        $this->assertNotEquals('wrong@smf.com', $user->getEmail());
    }

    public function testVersion()
    {
        $user = new User();

        $user->setVersion('0');
        $this->assertEquals('0', $user->getVersion());

        $user->setVersion('1');
        $this->assertEquals('1', $user->getVersion());

        $this->assertNotEquals('2', $user->getVersion());
    }

    public function testPassword()
    {
        $user = new User();

        $password = password_hash('12345', PASSWORD_BCRYPT, ['cost' => 13]);
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());

        $password = password_hash('abcdef', PASSWORD_BCRYPT, ['cost' => 12]);
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());

        $this->assertNotEquals('testingwithwrongpassword', $user->getPassword());
    }

    public function testSalt()
    {
        $user = new User();

        $salt = md5('abcdef');
        $user->setSalt($salt);
        $this->assertEquals($salt, $user->getSalt());

        $this->assertNotEquals('salt', $user->getSalt());
    }

    public function testRoles()
    {
        $user = new User();

        $roles = ['ADMIN','USER'];
        $user->setRoles(new ArrayCollection($roles));
        $this->assertEquals($roles, $user->getRoles());
        $this->assertNotEquals(['SUPERVISIOR'], $user->getRoles());

        $role = new Role(1);
        $user->addRole($role);
        $this->assertEquals(true, $user->removeRole($role));
    }

    public function testGroups()
    {
        $user = new User();

        $mg = [1,2];
        $user->setGroups(new ArrayCollection($mg));
        $this->assertEquals($mg, $user->getGroups());

        $marketGroup = new MarketGroup(1);
        $user->addGroup($marketGroup);
        $this->assertEquals(true, $user->removeGroup($marketGroup));
    }

    public function testForm()
    {
        $user = new User();
        $form = new Form(1);

        $user->addForm($form);
        $this->assertEquals(true, $user->removeForm($form));
        $this->assertEmpty($user->getForms());
    }

    public function testCreatedAt()
    {
        $user = new User();

        $current = new DateTime();
        $user->setCreatedAt($current);
        $this->assertEquals($current, $user->getCreatedAt());

        $date = new DateTime('2016-09-14 13:41:06');
        $user->setCreatedAt($date);
        $this->assertEquals($date, $user->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $user = new User();

        $current = new DateTime();
        $user->setUpdatedAt($current);
        $this->assertEquals($current, $user->getUpdatedAt());

        $date = new DateTime('2017-02-06 15:21:40');
        $user->setUpdatedAt($date);
        $this->assertEquals($date, $user->getUpdatedAt());
    }

    public function testDeletedAt()
    {
        $user = new User();
        $current = new DateTime();
        $user->setDeletedAt($current);
        $this->assertEquals($current, $user->getDeletedAt());
    }
}

